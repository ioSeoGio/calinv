<?php

namespace src\IssuerRatingCalculator\Static;

use app\models\FinancialInstrument\Share;

class FairSharePriceCalculator
{
    public static function calculate(
        Share $share,
    ): array {
        $return = [];
        foreach ($share->issuerRating->indicator as $indicatorData) {
            $return[] = ($indicatorData['longAsset'] - $indicatorData['longLiability']) / $share->issuerRating->shareAmount;
        }

        return $return;
    }
}
