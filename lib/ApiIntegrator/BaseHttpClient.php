<?php

namespace lib\ApiIntegrator;

use lib\Exception\UserException\ApiBadRequestException;
use lib\Exception\UserException\ApiInternalErrorException;
use lib\Exception\UserException\ApiLightTemporaryUnavailableException;
use RuntimeException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class BaseHttpClient
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {
    }

    /**
     * @throws RuntimeException
     * @throws ApiInternalErrorException 500 от api
     * @throws ApiBadRequestException 400 от api
     * @throws ApiLightTemporaryUnavailableException api сдохло временно
     */
    public function request(HttpMethod $method, string $url, array $options = []): ResponseInterface
    {
        try {
            $defaultOptions = [
                'timeout' => 1,
            ];

            $response = $this->httpClient->request($method->value, $url, array_merge($defaultOptions, $options));
            $response->getStatusCode();
        } catch (TransportExceptionInterface|RedirectionExceptionInterface $e) {
            throw new ApiLightTemporaryUnavailableException(previous: $e);
        } catch (ServerExceptionInterface $e) {
            throw new ApiInternalErrorException(previous: $e);
        } catch (ClientExceptionInterface $e) {
            throw new ApiBadRequestException(previous: $e);
        }

        return $response;
    }
}