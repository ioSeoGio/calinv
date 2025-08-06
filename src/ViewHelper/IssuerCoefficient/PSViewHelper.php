<?php

namespace src\ViewHelper\IssuerCoefficient;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\PSCalculator;

class PSViewHelper
{
    public static function render(Issuer $model): string
    {
        $result = '';

        foreach ($model->profitLossReports as $profitLossReport) {
            $value = PSCalculator::calculate($model, $profitLossReport);

            $result .= "$profitLossReport->_year: ";
            $result .= $value
                ? GoodBadValueViewHelper::inRange($value, min: 0, max: 2)
                : null;
            $result .= '<br>';
        }

        return $result;
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