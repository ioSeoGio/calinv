<?php

namespace src\Integration\Bik\EsgRating;

use lib\Helper\TrimHelper;

enum BikEsgForecast: string
{
    case positive = 'позитивный';
    case stable = 'стабильный';
    case uncertain = 'неопределенный';
    case unknown = 'неизвестен';

    public static function fromString(?string $forecast): self
    {
        if ($forecast === null) {
            return self::unknown;
        }

        $forecast = TrimHelper::trim($forecast);

        return self::tryFrom($forecast) ?: self::unknown;
    }
}