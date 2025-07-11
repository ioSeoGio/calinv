<?php

namespace src\Integration\FinanceReport\Mock;

use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\FinanceReport\Dto\FinanceReportAccountingBalanceDto;
use src\Integration\FinanceReport\Dto\FinanceReportCashFlowDto;
use src\Integration\FinanceReport\Dto\FinanceReportProfitLossDto;
use src\Integration\FinanceReport\FinanceReportFetcherInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class MockFinanceReportFetcher implements FinanceReportFetcherInterface
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
}