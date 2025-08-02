<?php

namespace src\Integration\Legat\Api;

use lib\ApiIntegrator\HttpMethod;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Legat\Dto\EmployeeAmount\EmployeeAmountDto;
use src\Integration\Legat\EmployeeAmountFetcherInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class LegatEmployeeAmountFetcher implements EmployeeAmountFetcherInterface
{
    public const string PATH = 'employees';

    public function __construct(
        private LegatApiHttpClient $client,
        private SerializerInterface $serializer,
    ) {
    }

    public function getEmployeeAmount(PayerIdentificationNumber $pid): EmployeeAmountDto
    {
        $response = $this->client->request(HttpMethod::GET, self::PATH, $pid);

        return $this->serializer->deserialize(
            $response->getContent(),
            EmployeeAmountDto::class,
            'json',
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ]
        );
    }
}
