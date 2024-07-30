<?php

namespace src\IssuerRatingCalculator\Static;

class GrowthCalculator
{
    public static function calculate(float $buyPrice, float $currentPrice): float
    {
        return ($currentPrice - $buyPrice) / $buyPrice;
    }
}
