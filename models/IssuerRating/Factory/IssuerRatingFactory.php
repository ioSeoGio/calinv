<?php

namespace app\models\IssuerRating\Factory;

use app\controllers\IssuerRatingCalculator\CalculateIndicatorForm;
use app\controllers\IssuerRatingCalculator\CalculateSimpleForm;
use app\models\IssuerRating\IssuerRating;

class IssuerRatingFactory
{
    public function __construct(
        private IssuerIndicatorGrowthFactory $issuerIndicatorGrowthFactory,
        private IssuerIndicatorFactory $issuerIndicatorFactory,
    ) {
    }

    public function create(CalculateSimpleForm $simpleForm, CalculateIndicatorForm ...$indicatorForms): IssuerRating
    {
        $issuerIndicators = $this->issuerIndicatorFactory->createMany(...$indicatorForms);

        $rating = new IssuerRating();
        $rating->load([
            'issuer' => $simpleForm->issuer,
            'bikScore' => $simpleForm->bikScore,
            'indicator' => $issuerIndicators,
            'shareAmount' => $simpleForm->shareAmount,
            'k1_standard' => $simpleForm->k1_standard,
            'k2_standard' => $simpleForm->k2_standard,
            'k3_standard' => $simpleForm->k3_standard,
        ], '');
        $this->update($rating);

        return $rating;
    }

    public function update(IssuerRating $rating, bool $recalculateChildren = false): void
    {
        $rating->indicatorGrowth = $this->issuerIndicatorGrowthFactory->createMany(...$rating->indicator);

        if ($recalculateChildren) {
            $indicators = [];
            foreach ($rating->indicator as $indicator) {
                $indicators[] = $this->issuerIndicatorFactory->update($indicator);
            }
            $rating->indicator = $indicators;
        }
    }
}
