<?php

namespace src\IssuerRatingCalculator\Static;

use app\models\FinancialInstrument\Share;
use app\models\IssuerRating\IssuerRating;

class FairSharePriceCalculator
{
    public static function calculateForLiquidation(
        Share $share,
    ): array {
        $capitalization = CapitalizationCalculator::calculate($share->issuerRating);

        $return = [];
        foreach ($share->issuerRating->indicator as $indicatorData) {
            $assets = $indicatorData['longAsset'] - $indicatorData['longLiability'];

            $return[] = $assets / $capitalization * $share->denomination;
        }

        return $return;
    }

    public static function calculateForEarning(
        Share $share,
    ): array {
        $counter = 0;
        $averagePE = 0;
        foreach ($share->issuerRating->indicator as $indicatorData) {
            $counter++;
            $averagePE += $indicatorData['PE'];
        }
        $averagePE /= $counter ?: 1;

        $discountRate = 0.20;
        $forecastedProfitGrowthRate = 0.05;
        $forecastedPeriodInYears = 3.0;

        $shareAmount = 0;
        foreach ($share->issuerRating->shares as $issuerShare) {
            $shareAmount += $issuerShare->volumeIssued;
        }

        $fairPrice = [];
        foreach ($share->issuerRating->indicator as $indicatorData) {
            $forecastedClearProfit = $indicatorData['profit'] + $indicatorData['profit'] * $forecastedProfitGrowthRate;
            $discountedForecastedClearProfit = $forecastedClearProfit / ((1.0 + $discountRate) ** $forecastedPeriodInYears);

            $fairCapitalization = $discountedForecastedClearProfit * $averagePE;
            $fairPrice[] = $fairCapitalization / ($shareAmount ?: 1);
        }

        return $fairPrice;
    }
}
