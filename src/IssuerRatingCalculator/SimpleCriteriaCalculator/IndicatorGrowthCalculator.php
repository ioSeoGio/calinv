<?php

namespace src\IssuerRatingCalculator\SimpleCriteriaCalculator;

use app\models\IssuerRating\IssuerIndicator;
use app\models\IssuerRating\SimpleCriteria\IssuerIndicatorGrowth;

class IndicatorGrowthCalculator
{
    /** @return IssuerIndicatorGrowth[] */
    public function execute(IssuerIndicator ...$issuerIndicators): array
    {
        $return = [];

        foreach ($issuerIndicators as $key => $issuerIndicator) {
            if (!next($issuerIndicators)) {
                break;
            }

            $return[] = new IssuerIndicatorGrowth(
                shortAssetGrowth: ($issuerIndicators[$key+1]->shortAsset - $issuerIndicator->shortAsset) / $issuerIndicator->shortAsset * 100,
                longAssetGrowth: ($issuerIndicators[$key+1]->longAsset - $issuerIndicator->longAsset) / $issuerIndicator->longAsset * 100,
                capitalGrowth: ($issuerIndicators[$key+1]->capital - $issuerIndicator->capital) / $issuerIndicator->capital * 100,
                shortLiabilityGrowth: ($issuerIndicators[$key+1]->shortLiability - $issuerIndicator->shortLiability) / $issuerIndicator->shortLiability * 100,
                longLiabilityGrowth: ($issuerIndicators[$key+1]->longLiability - $issuerIndicator->longLiability) / $issuerIndicator->longLiability * 100,
                profitGrowth: ($issuerIndicators[$key+1]->profit - $issuerIndicator->profit) / $issuerIndicator->profit * 100,
                dateFrom: $issuerIndicator->getDate(),
                dateTo: $issuerIndicator->getDate(),
            );
        }

        return $return;
    }
}
