<?php

declare(strict_types=1);

namespace lib;

final class UrlGenerator
{
    public static function addProtocolIfNeeded(string $url): string
    {
        $hostWithoutProtocol = !preg_match('~http(s)?://~', $url);
        return $hostWithoutProtocol ? 'https://' . $url : $url;
    }

    public function generateUrl(
        string $host,
        string $path,
        array $params = [],
        array $queryParams = [],
    ): string {
        $hostWithoutProtocol = !preg_match('~http(s)?://~', $host);
        $fullUrl = $hostWithoutProtocol ? 'https://' : '';

        if (empty($host) || empty($path)) {
            throw new \InvalidArgumentException('Url cannot be empty.');
        }

        $fullUrl = $fullUrl . rtrim($host, '/') . '/' . ltrim($path, '/');

        return $this->fillUrlQueryParams($fullUrl, $params, $queryParams);
    }

    private function fillUrlQueryParams(string $url, array $pathParams = [], array $queryParams = []): string
    {
        if ($pathParams !== []) {
            // Обязательно кодируем потому что они часть пути, symfony не находит эндпоинты если ввести спец. символы
            $url = sprintf($url, ...$this->encodeParams($pathParams));
        }
        if ($queryParams !== []) {
            // Эти параметры не кодируем, потому что они должны идти из кода, per-page/filter/offset и пр.
            $sign = str_contains($url, '?') ? '&' : '?';
            $query = http_build_query($queryParams);
            $url .= "{$sign}$query";
        }

        return $url;
    }

    private function encodeParams(array $pathParams): array
    {
        $params = [];

        foreach ($pathParams as $rawParam) {
            $params[] = urlencode($rawParam);
        }

        return $params;
    }
}
