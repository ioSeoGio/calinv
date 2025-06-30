<?php

namespace src\Integration\Bik\EsgRating;

use lib\Helper\TrimHelper;

enum EsgIssuerCategory: string
{
    case financial = 'Финансовая компания';
    case notFinancial = 'Нефинансовая компания';
    case freeEconomicZone = 'Свободная экономическая зона';
    case unknown = 'Неизвестна';

    public static function fromString(?string $category): self
    {
        if ($category === null) {
            return self::unknown;
        }

        $category = TrimHelper::trim($category);


        return self::tryFrom($category) ?: self::unknown;
    }
}