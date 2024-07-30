<?php

namespace src\IssuerRatingCalculator\SimpleCriteriaCalculator;

use app\models\IssuerRating\IssuerIndicator;

class PECalculator
{
    public static function execute(IssuerIndicator $issuerIndicator): float
    {
        return $issuerIndicator->capital / $issuerIndicator->profit;
    }
}
