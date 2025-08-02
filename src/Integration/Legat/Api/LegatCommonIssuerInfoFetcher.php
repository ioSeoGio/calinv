<?php

namespace src\Integration\Legat\Api;

use lib\ApiIntegrator\HttpMethod;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Legat\CommonIssuerInfoFetcherInterface;
use src\Integration\Legat\Dto\commonIssuerInfo\CommonIssuerInfoDto;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class LegatCommonIssuerInfoFetcher implements CommonIssuerInfoFetcherInterface
{
    public const string PATH = 'data';

    public function __construct(
        private LegatApiHttpClient $client,
        private SerializerInterface $serializer,
    ) {
    }

    public function getCommonInfo(PayerIdentificationNumber $pid): CommonIssuerInfoDto
    {
        $response = $this->client->request(HttpMethod::GET, self::PATH, $pid);

        return $this->serializer->deserialize(
            $response->getContent(),
            CommonIssuerInfoDto::class,
            'json',
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ]
        );
    }
}
