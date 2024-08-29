<?php

namespace src\IssuerRatingCalculator\SimpleCriteriaCalculator;

use app\models\IssuerRating\IssuerIndicator;
use app\models\IssuerRating\IssuerRating;
use src\IssuerRatingCalculator\Static\CapitalizationCalculator;

class PBCalculator
{
    public static function execute(IssuerIndicator $issuerIndicator, ?IssuerRating $issuerRating = null): ?float
    {
        if ($issuerRating === null) {
            return null;
        }

        $capitalization = CapitalizationCalculator::calculate($issuerRating);
        return $capitalization / $issuerIndicator->capital;
    }
}
