<?php

namespace app\models\IssuerRating\Factory;

use app\controllers\IssuerRatingCalculator\CalculateIndicatorForm;
use app\models\IssuerRating\IssuerIndicator;
use app\models\IssuerRating\IssuerRating;
use src\IssuerRatingCalculator\SimpleCriteriaCalculator\CoefficientCalculator;
use src\IssuerRatingCalculator\SimpleCriteriaCalculator\PBCalculator;
use src\IssuerRatingCalculator\SimpleCriteriaCalculator\PECalculator;

class IssuerIndicatorFactory
{
    public function createMany(CalculateIndicatorForm ...$indicatorForms): array
    {
        $issuerIndicators = [];
        foreach ($indicatorForms as $indicatorForm) {
            $issuerIndicator = new IssuerIndicator();
            $issuerIndicator->load([
                'shortAsset' => (float) $indicatorForm->shortAsset,
                'longAsset' => (float) $indicatorForm->longAsset,
                'capital' => (float) $indicatorForm->capital,
                'shortLiability' => (float) $indicatorForm->shortLiability,
                'longLiability' => (float) $indicatorForm->longLiability,
                'profit' => (float) $indicatorForm->profit,
                'date' => (new \DateTime())->format(DATE_ATOM)
            ], '');

            $this->update($issuerIndicator);
            $issuerIndicators[] = $issuerIndicator;
        }

        return $issuerIndicators;
    }

    public function update(IssuerIndicator $issuerIndicator, ?IssuerRating $issuerRating = null): IssuerIndicator
    {
        $issuerIndicator->load([
            'k1' => CoefficientCalculator::k1($issuerIndicator),
            'k2' => CoefficientCalculator::k2($issuerIndicator),
            'k3' => CoefficientCalculator::k3($issuerIndicator),
            'k4' => CoefficientCalculator::k4($issuerIndicator),
            'PE' => PECalculator::execute($issuerIndicator, $issuerRating),
            'PB' => PBCalculator::execute($issuerIndicator, $issuerRating),
        ], '');

        return $issuerIndicator;
    }
}
