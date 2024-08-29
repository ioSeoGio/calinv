<?php

namespace src\IssuerRatingCalculator\Static;

use app\models\IssuerRating\IssuerRating;

class CapitalizationCalculator
{
    public static function calculate(IssuerRating $issuerRating): float
    {
        $capitalization = 0;
        foreach ($issuerRating->shares as $issuerShare) {
            $capitalization += $issuerShare->volumeIssued * $issuerShare->denomination;
        }

        return $capitalization;
    }
}
