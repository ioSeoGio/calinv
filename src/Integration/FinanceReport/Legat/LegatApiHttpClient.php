<?php

namespace src\Integration\FinanceReport\Legat;

use lib\ApiIntegrator\BaseHttpClient;
use lib\ApiIntegrator\HttpMethod;
use lib\Exception\UserException\ApiBadRequestException;
use lib\Exception\UserException\ApiInternalErrorException;
use lib\UrlGenerator;
use RuntimeException;
use src\Entity\Issuer\PayerIdentificationNumber;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Класс для работы с API legat.by
 * Получает финансовую отчетность по УНП
 * https://api.legat.by/info/api2/by/methods документация
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
    public function request(HttpMethod $method, string $path, PayerIdentificationNumber $pid, \DateTimeImmutable $year): ResponseInterface
    {
        $url = $this->urlGenerator->generateUrl(self::BASE_URL, $path, queryParams: [
            'unp' => $pid->id,
            'key' => $_ENV['LEGAT_API_KEY'],
            'year' => $year->format('Y'),
        ]);
        $response = $this->httpClient->request($method, $url);

        return $response;
    }
}