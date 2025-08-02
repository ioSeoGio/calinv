<?php

namespace src\Integration\Legat\Api;

use lib\ApiIntegrator\BaseHttpClient;
use lib\ApiIntegrator\HttpMethod;
use lib\EnvGetter;
use lib\Exception\UserException\ApiBadRequestException;
use lib\Exception\UserException\ApiInternalErrorException;
use lib\UrlGenerator;
use RuntimeException;
use src\Entity\Issuer\PayerIdentificationNumber;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Класс для работы с API legat.by
 * Получает финансовую отчетность и другую информацию по УНП
 * https://api.legat.by/info/api2/by/methods документация
 *
 * Потенциально полезные методы:
 * /change - ивенты egr
 * /lights - экспресс проверка компании на надежность, совокупность кол-ва задолженностей и прочего говна
 * /massAddress - перечень компаний по одному адресу (палить однодневки)
 * /employees - среднесписочная численность работников (палить кризисы в компании)
 */
class LegatApiHttpClient
{
    public const string BASE_URL = 'https://api.legat.by/api2/by';

    public function __construct(
        private UrlGenerator $urlGenerator,
        private BaseHttpClient $httpClient,
    ) {
    }

    /**
     * @throws RuntimeException
     * @throws ApiInternalErrorException 500 от api
     * @throws ApiBadRequestException 400 от api
     */
    public function request(HttpMethod $method, string $path, PayerIdentificationNumber $pid, array $queryParams = []): ResponseInterface
    {
        $url = $this->urlGenerator->generateUrl(self::BASE_URL, $path, queryParams: array_merge([
            'unp' => $pid->id,
            'key' => EnvGetter::get('LEGAT_API_KEY'),
        ], $queryParams));

        $response = $this->httpClient->request($method, $url, [
            'timeout' => EnvGetter::getInt('LEGAT_TIMEOUT'),
        ]);

        return $response;
    }
}