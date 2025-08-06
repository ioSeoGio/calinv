<?php

namespace src\ViewHelper\IssuerCoefficient;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\PBCalculator;

class PBViewHelper
{
    public static function render(Issuer $model): string
    {
        $result = '';

        foreach ($model->accountBalanceReports as $accountBalanceReport) {
            $pb = PBCalculator::calculate($model, $accountBalanceReport);

            $result .= "$accountBalanceReport->_year: ";
            $result .= $pb
                ? GoodBadValueViewHelper::execute($pb, line: 1, moreBetter: false)
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
              <mi>Итоговый баланс (700 строка ОББ)</mi>
            </mrow>
          </mfrac>
        </math>';
    }
}