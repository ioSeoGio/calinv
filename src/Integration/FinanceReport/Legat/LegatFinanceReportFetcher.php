<?php

namespace src\Integration\FinanceReport\Legat;

use lib\ApiIntegrator\HttpMethod;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\FinanceReport\Dto\FinanceReportAccountingBalanceDto;
use src\Integration\FinanceReport\Dto\FinanceReportCapitalDto;
use src\Integration\FinanceReport\FinanceReportFetcherInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class LegatFinanceReportFetcher implements FinanceReportFetcherInterface
{
    public const string ACCOUNTING_BALANCE = 'financeBalance';
    public const string PROFIT_LOSS = 'financeProfitLoss';
    public const string CAPITAL = 'financeCapital';

    public function __construct(
        private LegatApiHttpClient $client,
        private SerializerInterface $serializer,
    ) {
    }

    public function getAccountingBalance(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportAccountingBalanceDto
    {
        $response = $this->client->request(HttpMethod::GET, self::ACCOUNTING_BALANCE, $pid, $year);

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

    public function getProfitLoss(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportAccountingBalanceDto
    {
        $response = $this->client->request(HttpMethod::GET, self::PROFIT_LOSS, $pid, $year);

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

    public function getCapital(PayerIdentificationNumber $pid, \DateTimeImmutable $year): FinanceReportAccountingBalanceDto
    {
        $response = $this->client->request(HttpMethod::GET, self::CAPITAL, $pid, $year);

        return $this->serializer->deserialize(
            $response->getContent(),
            FinanceReportCapitalDto::class,
            'json',
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ]
        );
    }
}
