<?php

namespace src\ViewHelper\IssuerCoefficient;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\K1Calculator;
use src\ViewHelper\Tools\ShowMoreBtn;

class K1ViewHelper
{
    public static function render(Issuer $issuer, int $max = 5): string
    {
        $result = '';

        $count = 0;
        $hiddenValues = '';
        $id = "more-k1-$issuer->id-container";
        foreach ($issuer->accountBalanceReports as $accountBalanceReport) {
            $value = K1Calculator::calculate($accountBalanceReport);
            $printValue = "$accountBalanceReport->_year: ";
            $printValue .= GoodBadValueViewHelper::execute($value, line: 1.15, moreBetter: true);
            $printValue .= '<br>';

            if ($count == $max) {
                $result .= ShowMoreBtn::renderBtn('ещё', $id);
            }

            if ($count >= $max) {
                $hiddenValues .= $printValue;
            } else {
                $result .= $printValue;
            }

            $count++;
        }

        if (!empty($hiddenValues)) {
            $result .= ShowMoreBtn::renderContainer($hiddenValues, $id);
        }

        return $result;
    }

    public static function getMathMLFormula(): string
    {
        return '<math xmlns="http://www.w3.org/1998/Math/MathML" class="math-large">
          <mfrac>
            <mrow>
              <mi>Краткосрочные активы (290 строка ОББ)</mi>
            </mrow>
            <mrow>
              <mi>Краткосрочные обязательства (690 строка ОББ)</mi>
            </mrow>
          </mfrac>
        </math>';
    }
}