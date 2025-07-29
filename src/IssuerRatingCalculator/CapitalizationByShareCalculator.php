<?php

namespace src\IssuerRatingCalculator;

use src\Entity\Issuer\Issuer;

class CapitalizationByShareCalculator
{
    public static function calculate(Issuer $issuer): float
    {
        $result = 0;
        foreach ($issuer->activeShares as $share) {
            $result += $share->totalIssuedAmount * $share->currentPrice;
        }

        return $result;
    }

    /**
     * Считает капитализацию в тысячах рублей
     */
    public static function calculateInGrands(Issuer $issuer): float
    {
        return self::calculate($issuer) / 1000;
    }
}