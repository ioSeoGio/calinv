<?php

namespace src\Integration\Egr;

use lib\ApiIntegrator\BaseHttpClient;
use lib\ApiIntegrator\HttpMethod;
use lib\ApiIntegrator\JsonHttpClient;
use lib\Exception\UserException\ApiBadRequestException;
use lib\Exception\UserException\ApiInternalErrorException;
use lib\Exception\UserException\ApiNotFoundException;
use lib\UrlGenerator;
use RuntimeException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Класс для работы с API Единого государственного реестра юр. лиц (ЮЛ)
 * Используется для получения общей информации об ЮЛ по УНП (учетный номер плательщика)
 * https://egr.gov.by/api/swagger-ui.html#/ swagger
 */
class EgrHttpClient
{
    public const string BASE_URL = 'https://egr.gov.by/api/';

    public const string ADDRESS_HISTORY = '/v2/egr/getAllAddressByRegNum/%s';
    public const string CURRENT_ADDRESS = '/v2/egr/getAddressByRegNum/%s';

    public const string LEGAL_NAME_HISTORY = '/v2/egr/getAllJurNamesByRegNum/%s';

    public const string TYPE_OF_ACTIVITY_HISTORY = '/v2/egr/getAllVEDByRegNum/%s';
    public const string CURRENT_TYPE_OF_ACTIVITY = '/v2/egr/getVEDByRegNum/%s';

    public const string BASE_INFO = '/v2/egr/getBaseInfoByRegNum/%s';
    public const string SHORT_STATUS = '/v2/egr/getShortInfoByRegNum/%s';

    public const string EVENTS = '/v2/egr/getEventByRegNum/%s';
    /* ФИО для ИП */
    public const string ipFio = '/v2/egr/getAllIPFIOByRegNum/%s';

    public function __construct(
        private UrlGenerator $urlGenerator,
        private BaseHttpClient $httpClient,
        private SerializerInterface $serializer,
    ) {
    }

    /**
     * @template T
     * @param class-string<T> $dtoClass
     * @return T
     *
     * @throws ApiInternalErrorException
     * @throws ApiBadRequestException
     */
    public function request(string $dtoClass, HttpMethod $method, string $path, array $pathParams = []): object|array
    {
        $url = $this->urlGenerator->generateUrl(self::BASE_URL, $path, $pathParams);

        try {
            $response = $this->httpClient->request($method, $url, [
                'timeout' => 1,
            ]);

            if ($response->getStatusCode() === 204) {
                throw new ApiNotFoundException();
            }

            $content = $response->getContent();
            return $this->serializer->deserialize($content, $dtoClass, 'json', [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ]);
        } catch (ExceptionInterface $e) {
            throw new RuntimeException(previous: $e);
        }
    }
}