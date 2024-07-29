<?php

namespace src\IssuerRatingCalculator\SimpleCriteriaCalculator;

use app\models\IssuerRating\IssuerIndicator;

class CoefficientCalculator
{
    public static function k1(IssuerIndicator $issuerIndicator): float
    {
        return $issuerIndicator->shortAsset / ($issuerIndicator->shortLiability ?: 1);
    }

    public static function k2(IssuerIndicator $issuerIndicator): float
    {
        return ($issuerIndicator->capital + $issuerIndicator->longLiability - $issuerIndicator->longAsset)
            / ($issuerIndicator->shortAsset ?: 1);
    }

    public static function k3(IssuerIndicator $issuerIndicator): float
    {
        return ($issuerIndicator->shortLiability + $issuerIndicator->longLiability)
            / ($issuerIndicator->shortAsset + $issuerIndicator->longAsset);
    }
}
