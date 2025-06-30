<?php

namespace src\Integration\Bik;

use lib\ApiIntegrator\BaseHttpClient;
use lib\ApiIntegrator\HttpMethod;
use lib\UrlGenerator;
use Symfony\Contracts\HttpClient\ResponseInterface;

class BikHttpClient
{
    public const string BASE_URL = 'https://bikratings.by/';

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
            'timeout' => 5,
            'body' => $data,
        ]);

        return $response;
    }
}