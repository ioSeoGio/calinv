<?php

namespace src\Integration\Bik\CreditRating;

use lib\Helper\TrimHelper;

enum BikCreditRatingForecast: string
{
    case positive = 'Позитивный';
    case negative = 'Негативный';
    case stable = 'Стабильный';
    case uncertain = 'Неопределенный';
    case unknown = 'неизвестен';

    public static function fromString(?string $forecast): ?self
    {
        if (empty($forecast)) {
            return null;
        }

        $forecast = TrimHelper::trim($forecast);

        return self::tryFrom($forecast) ?: self::unknown;
    }
}