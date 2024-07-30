<?php

namespace app\models\IssuerRating\Factory;

use app\models\IssuerRating\IssuerIndicator;
use app\models\IssuerRating\SimpleCriteria\IssuerIndicatorGrowth;

class IssuerIndicatorGrowthFactory
{
    public function createMany(IssuerIndicator ...$issuerIndicators): array
    {
        $return = [];

        foreach ($issuerIndicators as $key => $issuerIndicator) {
            if (!next($issuerIndicators)) {
                break;
            }

            $issuerIndicatorGrowth = new IssuerIndicatorGrowth();
            $issuerIndicatorGrowth->load([
                'shortAssetGrowth' => ($issuerIndicators[$key+1]->shortAsset - $issuerIndicator->shortAsset) / ($issuerIndicator->shortAsset ?: 1) * 100,
                'longAssetGrowth' => ($issuerIndicators[$key+1]->longAsset - $issuerIndicator->longAsset) / ($issuerIndicator->longAsset ?: 1) * 100,
                'capitalGrowth' => ($issuerIndicators[$key+1]->capital - $issuerIndicator->capital) / ($issuerIndicator->capital ?: 1) * 100,
                'shortLiabilityGrowth' => ($issuerIndicators[$key+1]->shortLiability - $issuerIndicator->shortLiability) / ($issuerIndicator->shortLiability ?: 1) * 100,
                'longLiabilityGrowth' => ($issuerIndicators[$key+1]->longLiability - $issuerIndicator->longLiability) / ($issuerIndicator->longLiability ?: 1) * 100,
                'profitGrowth' => ($issuerIndicators[$key+1]->profit - $issuerIndicator->profit) / ($issuerIndicator->profit ?: 1) * 100,
                'dateFrom' => $issuerIndicator->getDate()->format(DATE_ATOM),
                'dateTo' => $issuerIndicator->getDate()->format(DATE_ATOM),
            ], '');

            $return[] = $issuerIndicatorGrowth;
        }

        return $return;
    }
}
