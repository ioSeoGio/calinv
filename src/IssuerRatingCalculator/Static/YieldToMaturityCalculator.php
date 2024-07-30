<?php

namespace src\IssuerRatingCalculator\Static;

use DateTime;

class YieldToMaturityCalculator
{
    public static function calculate(
        float $denomination,
        float $buyPrice,
        float $rate,
        \DateTime $maturityDate,
        \DateTime $buyDate = new DateTime(),
    ): float {
        $diff = $maturityDate->diff($buyDate);
        $daysToMaturity = $diff->days;
        if ($diff->invert === 0) {
            return 0;
        }
        $yearsToMaturity = $daysToMaturity/365;
        $allCouponIncome = $denomination * ($rate/100) * $yearsToMaturity;
        $allIncomeInPercent = (($denomination - $buyPrice) + $allCouponIncome) / $buyPrice * 100;

        return $allIncomeInPercent / $yearsToMaturity;
    }
}
