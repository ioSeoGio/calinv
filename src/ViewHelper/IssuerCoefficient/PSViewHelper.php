<?php

namespace src\ViewHelper\IssuerCoefficient;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\PSCalculator;
use src\ViewHelper\Tools\ShowMoreContainer;

class PSViewHelper
{
    public static function render(Issuer $model, ?float $capitalizationInGrands = null): string
    {
        $values = [];

        foreach ($model->profitLossReports as $profitLossReport) {
            $value = $capitalizationInGrands
                ? PSCalculator::calculateByCapitalization($capitalizationInGrands, $profitLossReport)
                : PSCalculator::calculate($model, $profitLossReport);

            $result = "$profitLossReport->_year: ";
            $result .= $value
                ? GoodBadValueViewHelper::inRangeAsBadge($value, min: 0, max: 2)
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
              <mi>Выручка от реализации продукции, товаров, работ, услуг (10 строка ОПиУ)</mi>
            </mrow>
          </mfrac>
        </math>';
    }
}