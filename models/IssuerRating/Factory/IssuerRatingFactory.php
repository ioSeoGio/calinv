<?php

namespace app\models\IssuerRating\Factory;

use app\controllers\IssuerRatingCalculator\CalculateIndicatorForm;
use app\controllers\IssuerRatingCalculator\CalculateSimpleForm;
use app\models\IssuerRating\IssuerIndicator;
use app\models\IssuerRating\IssuerRating;
use src\IssuerRatingCalculator\SimpleCriteriaCalculator\IndicatorGrowthCalculator;

class IssuerRatingFactory
{
    public function __construct(
        private IndicatorGrowthCalculator $indicatorGrowthCalculator,
    ) {
    }

    public function create(CalculateSimpleForm $simpleForm, CalculateIndicatorForm ...$indicatorForms): IssuerRating
    {
        $issuerIndicators = [];
        foreach ($indicatorForms as $indicatorForm) {
            $issuerIndicators[] = new IssuerIndicator(
                shortAsset: (float) $indicatorForm->shortAsset,
                longAsset: (float) $indicatorForm->longAsset,
                capital: (float) $indicatorForm->capital,
                shortLiability: (float) $indicatorForm->shortLiability,
                longLiability: (float) $indicatorForm->longLiability,
                profit: (float) $indicatorForm->profit,
                date: new \DateTimeImmutable(),
            );
        }

        $rating = new IssuerRating();
        $rating->load([
            'issuer' => $simpleForm->issuer,
            'bikScore' => $simpleForm->bikScore,
            'indicator' => $issuerIndicators,
            'indicatorGrowth' => $this->indicatorGrowthCalculator->execute(...$issuerIndicators),
            'k1_standard' => $simpleForm->k1_standard,
            'k2_standard' => $simpleForm->k2_standard,
            'k3_standard' => $simpleForm->k3_standard,
        ], '');

        return $rating;
    }
}
