<?php

namespace src\Integration\Legat\Api;

use lib\ApiIntegrator\HttpMethod;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Legat\Dto\egrEventDto\LegatEgrEventsDto;
use src\Integration\Legat\LegatEgrEventsFetcherInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class LegatLegatEgrEventFetcher implements LegatEgrEventsFetcherInterface
{
    public const string PATH = 'change';

    public function __construct(
        private LegatApiHttpClient $client,
        private SerializerInterface $serializer,
    ) {
    }

    public function getEvents(PayerIdentificationNumber $pid): LegatEgrEventsDto
    {
        $response = $this->client->request(HttpMethod::GET, self::PATH, $pid);

        return $this->serializer->deserialize(
            $response->getContent(),
            LegatEgrEventsDto::class,
            'json',
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ]
        );
    }
}
