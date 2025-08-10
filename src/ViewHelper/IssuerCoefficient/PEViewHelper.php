<?php

namespace src\ViewHelper\IssuerCoefficient;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\PECalculator;
use src\ViewHelper\Tools\ShowMoreContainer;

class PEViewHelper
{
    public static function render(Issuer $model, ?float $capitalizationInGrands = null): string
    {
        $values = [];

        foreach ($model->profitLossReports as $profitLossReport) {
            $pe = $capitalizationInGrands
                ? PECalculator::calculateByCapitalization($capitalizationInGrands, $profitLossReport)
                : PECalculator::calculate($model, $profitLossReport);

            $result = "$profitLossReport->_year: ";
            $result .= $pe
                ? GoodBadValueViewHelper::asBadge($pe, line: 10, moreBetter: false)
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
              <mi>Совокупная прибыль (240 строка ОПиУ)</mi>
            </mrow>
          </mfrac>
        </math>';
    }
}