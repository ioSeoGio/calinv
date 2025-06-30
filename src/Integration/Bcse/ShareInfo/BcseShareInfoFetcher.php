<?php

namespace src\Integration\Bcse\ShareInfo;

use lib\ApiIntegrator\HttpMethod;
use lib\Exception\UserException\ApiNotFoundException;
use lib\Exception\UserException\ApiSimpleBadResponseException;
use simplehtmldom\HtmlDocument;
use simplehtmldom\HtmlNode;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Entity\Share\ShareRegisterNumber;
use src\Integration\Bcse\BcseHttpClient;
use src\Integration\CentralDepo\IssuerAndSharesInfo\CentralDepoIssuerAndSharesInfoDto;
use src\Integration\CentralDepo\IssuerAndSharesInfo\ShareInfoDto;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BcseShareInfoFetcher
{
    public const string PATH = '/stock/securitydirectory/%s/%s';

    public function __construct(
        private BcseHttpClient $httpClient,
        private ValidatorInterface $validator,
    ) {
    }

    public function get(PayerIdentificationNumber $pid, ShareRegisterNumber $registerNumberDto): ?BcseShareLastDealDto
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

        $dto = new BcseShareLastDealDto(
            date: $lastDealSection->childNodes(0)->childNodes(1)->innertext(),
            price: (float) $lastDealSection->childNodes(1)->childNodes(1)->childNodes(0)->innertext(),
            changeFromPreviousDeal: (float) $lastDealSection->childNodes(1)->childNodes(1)->childNodes(1)->innertext(),
            changeFromPreviousDealPercent: (float) $lastDealSection->childNodes(1)->childNodes(1)->childNodes(2)->innertext(),
        );
        return $dto;
    }
}