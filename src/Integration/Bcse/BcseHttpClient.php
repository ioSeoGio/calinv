<?php

namespace src\Integration\Bcse;

use lib\ApiIntegrator\BaseHttpClient;
use lib\ApiIntegrator\HttpMethod;
use lib\EnvGetter;
use lib\UrlGenerator;
use Symfony\Contracts\HttpClient\ResponseInterface;

class BcseHttpClient
{
    public const string BASE_URL = 'https://www.bcse.by';

    public function __construct(
        private UrlGenerator $urlGenerator,
        private BaseHttpClient $httpClient,
    ) {
    }

    public function request(HttpMethod $method, string $path, array $data = []): ResponseInterface
    {
        $url = $this->urlGenerator->generateUrl(self::BASE_URL, $path);

        $response = $this->httpClient->request($method, $url, [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body' => $data,
            'timeout' => EnvGetter::getInt('BCSE_API_TIMEOUT'),
        ]);

        return $response;
    }
}