<?php

namespace app\models\IssuerRating\Factory;

use app\controllers\IssuerRatingCalculator\CalculateForm;
use app\models\IssuerRating\IssuerIndicator;
use app\models\IssuerRating\IssuerRating;
use src\IssuerRatingCalculator\SimpleCriteriaCalculator\CoefficientCalculator;
use src\IssuerRatingCalculator\SimpleCriteriaCalculator\IndicatorGrowthCalculator;

class IssuerRatingFactory
{
    public function __construct(
        private IndicatorGrowthCalculator $indicatorGrowthCalculator,
        private CoefficientCalculator $coefficientCalculator,
    ) {
    }

    public function create(CalculateForm $form): IssuerRating
    {
        $issuerIndicators = [];
        foreach ($form->shortAssetPerYearData as $key => $assetPerYear) {
            $issuerIndicators[] = new IssuerIndicator(
                shortAsset: $assetPerYear,
                longAsset: $form->longAssetPerYearData[$key],
                capital: $form->capitalPerYearData[$key],
                shortLiability: $form->shortLiabilityPerYearData[$key],
                longLiability: $form->longLiabilityPerYearData[$key],
                profit: $form->profitPerYearData[$key],
                date: new \DateTimeImmutable(),
            );
        }

        $rating = new IssuerRating();
        $rating->load([
            'issuer' => $form->issuer,
            'bikScore' => $form->bikScore,
            'indicator' => $issuerIndicators,
            'indicatorGrowth' => $this->indicatorGrowthCalculator->execute(...$issuerIndicators),
            'calculatedCoefficient' => $this->coefficientCalculator->execute(...$issuerIndicators),
        ], '');

        return $rating;
    }
}
