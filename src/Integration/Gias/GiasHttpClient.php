<?php

namespace src\Integration\Gias;

use lib\ApiIntegrator\BaseHttpClient;
use lib\ApiIntegrator\HttpMethod;
use lib\UrlGenerator;

/**
 * Класс для работы с API gias
 * Государственная Информационно-аналитическая Система Управления Государственными Закупками (ГИАС)
 */
class GiasHttpClient
{
    public const string BASE_URL = 'https://gias.by/';
    public const string UNRELIABLE_SUPPLIER_LIST = '/directory/api/v1/locked_suppliers/page';

    public function __construct(
        private UrlGenerator $urlGenerator,
        private BaseHttpClient $httpClient,
    ) {
    }

    public function request(HttpMethod $method, string $path, array $queryParams = []): object|array
    {
        $url = $this->urlGenerator->generateUrl(host: self::BASE_URL, path: $path, queryParams: $queryParams);

        return $this->httpClient->request($method, $url, [
            'timeout' => 1,
        ]);
    }
}