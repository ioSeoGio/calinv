<?php

namespace src\Integration\Bcse\ShareInfo;

use lib\ApiIntegrator\HttpMethod;
use lib\Exception\UserException\ApiBadResponseException;
use lib\Exception\UserException\ApiNotFoundException;
use lib\Exception\UserException\ApiSimpleBadResponseException;
use lib\Helper\TrimHelper;
use lib\Transformer\FloatTransformer;
use NumberFormatter;
use simplehtmldom\HtmlDocument;
use simplehtmldom\HtmlNode;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Entity\Share\ShareRegisterNumber;
use src\Integration\Bcse\BcseHttpClient;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Yii;

class BcseShareInfoFetcher
{
    public const string PATH = '/stock/securitydirectory/%s/%s';

    public function __construct(
        private BcseHttpClient $httpClient,
        private ValidatorInterface $validator,
    ) {
    }

    public function get(PayerIdentificationNumber $pid, ShareRegisterNumber $registerNumberDto): ?BcseShareFullInfoDto
    {
        $response = $this->httpClient->request(
            HttpMethod::GET,
            sprintf(self::PATH, $pid->id, $registerNumberDto->number)
        );
        $dom = new HtmlDocument($response->getContent());

        /** @var HtmlNode $lastDealSection */
        $lastDealSection = $dom->find('#wrap-last-change')[0] ?? null;

        if ($lastDealSection === null) {
            throw new ApiNotFoundException("Не найдена акция {$registerNumberDto->number} эмитента с УНП {$pid->id}");
        }

        $hasNoDealLastYear = $lastDealSection->find('.comb');
        if ($hasNoDealLastYear) {
            return null;
        }

        $lastDealDto = new BcseShareLastDealDto(
            date: $lastDealSection->childNodes(0)->childNodes(1)->innertext(),
            price: FloatTransformer::fromShitToFloat($lastDealSection->childNodes(1)->childNodes(1)->childNodes(0)->innertext()),
            changeFromPreviousDeal: FloatTransformer::fromShitToFloat($lastDealSection->childNodes(1)->childNodes(1)->childNodes(1)->innertext()),
            changeFromPreviousDealPercent: FloatTransformer::fromShitToFloat($lastDealSection->childNodes(1)->childNodes(1)->childNodes(2)->innertext()),
        );

        $secondaryDealSection = $dom->find('#mss-2-table')[0] ?? null;
        if ($secondaryDealSection === null) {
            throw new ApiSimpleBadResponseException("Не найдены записи о сделках акции {$registerNumberDto->number} эмитента с УНП {$pid->id}");
        }

        /** @var ?HtmlNode $firstRow */
        $firstRow = $secondaryDealSection->find('tr')[0] ?? null;
        if ($firstRow === null) {
            throw new ApiSimpleBadResponseException("Не найдены записи о сделках акции {$registerNumberDto->number} эмитента с УНП {$pid->id}");
        }

        if (TrimHelper::trim($firstRow->innertext()) === 'Данных нет') {
            return new BcseShareFullInfoDto($lastDealDto);
        }

        $dealDtos = [];
        foreach ($secondaryDealSection->find('tbody tr') as $row) {
            $dealDtos[] = new BcseShareDealRecordDto(
                date: $row->childNodes(0)->innertext(),
                currency: $row->childNodes(3)->innertext(),
                minPrice: $row->childNodes(4)->innertext(),
                maxPrice: $row->childNodes(5)->innertext(),
                weightedAveragePrice: $row->childNodes(6)->innertext(),
                totalSum: $row->childNodes(10)->innertext(),
                totalAmount: $row->childNodes(11)->innertext(),
                totalDealAmount: $row->childNodes(12)->innertext(),
            );
        }

        $dto = new BcseShareFullInfoDto(
            $lastDealDto,
            ...$dealDtos
        );

        return $dto;
    }
}