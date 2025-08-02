<?php

namespace src\Integration\Legat\Api;

use lib\ApiIntegrator\HttpMethod;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Legat\Dto\FinanceReportAccountingBalanceDto;
use src\Integration\Legat\Dto\FinanceReportCashFlowDto;
use src\Integration\Legat\Dto\FinanceReportProfitLossDto;
use src\Integration\Legat\FinanceReportFetcherInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class LegatFinanceReportFetcher implements FinanceReportFetcherInterface
{
    public const string ACCOUNTING_BALANCE = 'financeBalance';
    public const string PROFIT_LOSS = 'financeProfitLoss';
    public const string TRAFFIC = 'financeTraffic';

    public function __construct(
        private LegatApiHttpClient $client,
        private SerializerInterface $serializer,
    ) {
    }

    public function getAccountingBalance(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportAccountingBalanceDto
    {
        $response = $this->client->request(HttpMethod::GET, self::ACCOUNTING_BALANCE, $pid, [
            'year' => $year->format('Y'),
        ]);

        return $this->serializer->deserialize(
            $response->getContent(),
            FinanceReportAccountingBalanceDto::class,
            'json',
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ]
        );
    }

    public function getProfitLoss(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportProfitLossDto
    {
        $response = $this->client->request(HttpMethod::GET, self::PROFIT_LOSS, $pid, [
            'year' => $year->format('Y'),
        ]);

        return $this->serializer->deserialize(
            $response->getContent(),
            FinanceReportProfitLossDto::class,
            'json',
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ]
        );
    }

    public function getCashFlowReport(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportCashFlowDto
    {
        $response = $this->client->request(HttpMethod::GET, self::TRAFFIC, $pid, [
            'year' => $year->format('Y'),
        ]);

        return $this->serializer->deserialize(
            $response->getContent(),
            FinanceReportCashFlowDto::class,
            'json',
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ]
        );
    }
}
