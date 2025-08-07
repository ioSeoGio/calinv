<?php

namespace src\ViewHelper\IssuerCoefficient;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\K3Calculator;
use src\ViewHelper\Tools\ShowMoreBtn;

class K3ViewHelper
{
    public static function render(Issuer $issuer, int $max = 5): string
    {
        $result = '';

        $count = 0;
        $hiddenValues = '';
        $id = "more-k3-$issuer->id-container";
        foreach ($issuer->accountBalanceReports as $accountBalanceReport) {
            $value = K3Calculator::calculate($accountBalanceReport);
            $printValue = "$accountBalanceReport->_year: ";
            $printValue .= GoodBadValueViewHelper::execute($value, line: 0.85, moreBetter: false);
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
              <mi>Краткосрочные обязательства (690 строка ОББ)</mi>
              <mo>+</mo>
              <mi>Долгосрочные обязательства (590 строка ОББ)</mi>
            </mrow>
            <mrow>
              <mi>Баланс активов (300 строка ОББ)</mi>
            </mrow>
          </mfrac>
        </math>';
    }
}