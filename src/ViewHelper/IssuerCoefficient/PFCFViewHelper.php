<?php

namespace src\ViewHelper\IssuerCoefficient;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\PFCFCalculator;
use src\ViewHelper\Tools\ShowMoreContainer;

class PFCFViewHelper
{
    public static function render(Issuer $model, ?float $capitalizationInGrands = null): string
    {
        $values = [];

        foreach ($model->cashFlowReports as $cashFlowReport) {
            $value = $capitalizationInGrands
                ? PFCFCalculator::calculateByCapitalization($capitalizationInGrands, $cashFlowReport)
                : PFCFCalculator::calculate($model, $cashFlowReport);

            $result = "$cashFlowReport->_year: ";
            $result .= $value
                ? GoodBadValueViewHelper::inRangeAsBadge($value, min: 0, max: 20)
                : null;
            $result .= '<br>';
            $values[] = $result;
        }

        return ShowMoreContainer::render($values);
    }

    public static function getMathMLFormula(): string
    {
        return '<math xmlns="http://www.w3.org/1998/Math/MathML" class="math-large">
          <mfrac>
            <mrow>
              <mi>Капитализация</mi>
            </mrow>
            <mrow>
              <mi>Результат ДДС по текущей, инвестиционной и финансовой деятельности (110 строка ОДДС)</mi>
            </mrow>
          </mfrac>
        </math>';
    }
}