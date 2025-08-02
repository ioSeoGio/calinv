<?php

namespace src\Integration\Legat\Mock;

use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Legat\CommonIssuerInfoFetcherInterface;
use src\Integration\Legat\Dto\commonIssuerInfo\CommonIssuerInfoDto;
use src\Integration\Legat\Dto\egrEventDto\LegatEgrEventsDto;
use src\Integration\Legat\Dto\FinanceReportAccountingBalanceDto;
use src\Integration\Legat\Dto\FinanceReportCashFlowDto;
use src\Integration\Legat\Dto\FinanceReportProfitLossDto;
use src\Integration\Legat\LegatEgrEventsFetcherInterface;
use src\Integration\Legat\FinanceReportFetcherInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class MockLegatFetcherLegat implements FinanceReportFetcherInterface, CommonIssuerInfoFetcherInterface, LegatEgrEventsFetcherInterface
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    public function getAccountingBalance(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportAccountingBalanceDto
    {
        return $this->serializer->deserialize(
            file_get_contents(__DIR__ . '/accounting-balance.json'),
            FinanceReportAccountingBalanceDto::class,
            'json',
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ],
        );
    }

    public function getProfitLoss(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportProfitLossDto
    {
        return $this->serializer->deserialize(
            file_get_contents(__DIR__ . '/profit-loss.json'),
            FinanceReportProfitLossDto::class,
            'json',
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ],
        );
    }

    public function getCashFlowReport(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportCashFlowDto
    {
        return $this->serializer->deserialize(
            file_get_contents(__DIR__ . '/cash-flow.json'),
            FinanceReportCashFlowDto::class,
            'json',
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ],
        );
    }

    public function getCommonInfo(PayerIdentificationNumber $pid): CommonIssuerInfoDto
    {
        return $this->serializer->deserialize(
            file_get_contents(__DIR__ . '/common-issuer-info.json'),
            CommonIssuerInfoDto::class,
            'json',
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ],
        );
    }

    public function getEvents(PayerIdentificationNumber $pid): LegatEgrEventsDto
    {
        return $this->serializer->deserialize(
            file_get_contents(__DIR__ . '/egr-events.json'),
            LegatEgrEventsDto::class,
            'json',
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ],
        );
    }
}