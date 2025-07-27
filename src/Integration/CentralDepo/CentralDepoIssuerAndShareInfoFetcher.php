<?php

namespace src\Integration\CentralDepo;

use lib\ApiIntegrator\HttpMethod;
use lib\Exception\UserException\ApiNotFoundException;
use lib\Exception\UserException\ApiSimpleBadResponseException;
use simplehtmldom\HtmlDocument;
use simplehtmldom\HtmlNode;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\CentralDepo\IssuerAndSharesInfo\CentralDepoIssuerAndSharesInfoDto;
use src\Integration\CentralDepo\IssuerAndSharesInfo\ShareInfoDto;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CentralDepoIssuerAndShareInfoFetcher
{
    public const string PATH = '/spravochnaya-informatsiya/emitent/';

    public function __construct(
        private CentralDepoHttpClient $httpClient,
        private ValidatorInterface $validator,
    ) {
    }

    public function get(PayerIdentificationNumber $pid): CentralDepoIssuerAndSharesInfoDto
    {
        $response = $this->httpClient->request(HttpMethod::POST, self::PATH, [
            'ajax_get' => '',
            'NAME' => '',
            'PROPERTY_1213' => $pid->id,
            'PROPERTY_1220' => '',
            'PROPERTY_1242' => '',
            'set_filter' => 'Найти',
        ]);
        $dom = new HtmlDocument($response->getContent());

        $generalInfoTables = $dom->find('.table-color tbody');
        $sections = $dom->find('.table-container');

        /** @var HtmlNode $generalInfoTable */
        $generalInfoTable = $generalInfoTables[0] ?? null;
        $sharesSection = null;

        /** @var HtmlNode $section */
        foreach ($sections as $section) {
            $header = $section->parentNode()->find('h2.sub-header')[0];
            if ($header->innertext === 'Акции') {
                $sharesSection = $section->find('table tbody')[0];
            }
        }

        if ($generalInfoTable === null) {
            throw new ApiNotFoundException("Не найден эмитент с УНП $pid->id");
        }
        if (count($generalInfoTables) > 1 ) {
            throw new ApiSimpleBadResponseException("Для эмитента с УНП $pid->id api ответило невалидными данными");
        }

        $shareDtos = [];
        /** @var HtmlNode $shareRow */
        foreach ($sharesSection?->childNodes() ?: [] as $shareRow) {
            $shareDtos[] = new ShareInfoDto(
                nationalId: $shareRow->childNodes(0)->innertext(),
                orderedIssueId: $shareRow->childNodes(4)->innertext(),
                issueDate: $shareRow->childNodes(5)->innertext(),
                registerNumber: $shareRow->childNodes(6)->innertext(),
                denominationPrice: $shareRow->childNodes(7)->innertext(),
                totalIssuedAmount: (int) $shareRow->childNodes(10)->innertext(),
                simpleIssuedAmount: $shareRow->childNodes(8)->innertext(),
                privilegedIssuedAmount: $shareRow->childNodes(9)->innertext(),
                closingDate: $shareRow->childNodes(11)->innertext(),
            );
        }

        $dto = new CentralDepoIssuerAndSharesInfoDto(
            fullName: $generalInfoTable->childNodes(0)->childNodes(1)->innertext(),
            shortName: $generalInfoTable->childNodes(1)->childNodes(1)->innertext(),
            issuerCode: $generalInfoTable->childNodes(2)->childNodes(1)->innertext(),
            pid: $generalInfoTable->childNodes(3)->childNodes(1)->innertext(),
            address: $generalInfoTable->childNodes(4)->childNodes(1)->innertext(),
            phone: $generalInfoTable->childNodes(5)->childNodes(1)->innertext(),
            depo: $generalInfoTable->childNodes(6)->childNodes(1)->innertext(),
            authorizedCapital: $generalInfoTable->childNodes(7)->childNodes(1)->innertext(),
            legalStatus: CentralDepoLegalStatus::makeFrom($generalInfoTable->childNodes(8)->childNodes(1)->innertext())->toLegalStatus(),
            shareDtos: $shareDtos,
        );
        $this->validator->validate($dto);

        return $dto;
    }
}