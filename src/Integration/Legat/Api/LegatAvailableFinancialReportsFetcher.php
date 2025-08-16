<?php

namespace src\Integration\Legat\Api;

use lib\ApiIntegrator\HttpMethod;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Legat\Dto\AvailableFinancialReports\AllAvailableFinancialReportsDto;
use src\Integration\Legat\LegatAvailableFinancialReportsFetcherInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class LegatAvailableFinancialReportsFetcher implements LegatAvailableFinancialReportsFetcherInterface
{
    public const string PATH = 'financeMethods';

    public function __construct(
        private LegatApiHttpClient $client,
        private SerializerInterface $serializer,
    ) {
    }

    public function getAvailableReports(PayerIdentificationNumber $pid): AllAvailableFinancialReportsDto
    {
        $response = $this->client->request(HttpMethod::GET, self::PATH, $pid);

        return $this->serializer->deserialize(
            $response->getContent(),
            AllAvailableFinancialReportsDto::class,
            'json',
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ]
        );
    }
}
