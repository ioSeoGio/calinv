<?php

namespace src\IssuerRatingCalculator\SimpleCriteriaCalculator;

use app\models\IssuerRating\IssuerIndicator;
use app\models\IssuerRating\SimpleCriteria\CalculatedCoefficient;

class CoefficientCalculator
{
    /** @return CalculatedCoefficient[] */
    public function execute(IssuerIndicator ...$issuerIndicators): array
    {
        $return = [];
        foreach ($issuerIndicators as $issuerIndicator) {
            $return[] = new CalculatedCoefficient(
                k1: $issuerIndicator->shortAsset / $issuerIndicator->shortLiability,
                k2: ($issuerIndicator->capital + $issuerIndicator->longLiability - $issuerIndicator->longAsset) / $issuerIndicator->shortAsset,
                k3: ($issuerIndicator->shortLiability + $issuerIndicator->longLiability) / ($issuerIndicator->shortAsset + $issuerIndicator->longAsset),
                date: $issuerIndicator->getDate(),
            );
        }

        return $return;
    }
}
