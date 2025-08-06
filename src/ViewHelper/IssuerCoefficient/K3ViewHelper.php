<?php

namespace src\ViewHelper\IssuerCoefficient;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\K3Calculator;

class K3ViewHelper
{
    public static function render(Issuer $issuer): string
    {
        $result = '';

        foreach ($issuer->accountBalanceReports as $accountBalanceReport) {
            $value = K3Calculator::calculate($accountBalanceReport);
            $result .= "$accountBalanceReport->_year: ";
            $result .= GoodBadValueViewHelper::execute($value, line: 0.85, moreBetter: false);
            $result .= '<br>';
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