<?php

namespace src\Integration\Bcse\AllShareList;

use lib\ApiIntegrator\HttpMethod;
use src\Integration\Bcse\BcseHttpClient;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Yii;

class BcseAllActiveShareFetcher
{
    public const string PATH = '/Stock/GetSCatalog';

    public function __construct(
        private BcseHttpClient $httpClient,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    public function get(): BcseShareBaseInfoList
    {
        $response = $this->httpClient->request(
            HttpMethod::GET,
            self::PATH,
            queryParams: [
                'kinds' => '101,102,801,802',
                'regnumber' => '',
                'accessTrades' => 'True',
            ],
        );

        $dto = $this->serializer->deserialize($response->getContent(), BcseShareBaseInfoList::class, 'json');
        $this->validator->validate($dto);

        return $dto;
    }
}