<?php

namespace src\ViewHelper\IssuerCoefficient;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\K2Calculator;

class K2ViewHelper
{
    public static function render(Issuer $issuer): string
    {
        $result = '';

        foreach ($issuer->accountBalanceReports as $accountBalanceReport) {
            $value = K2Calculator::calculate($accountBalanceReport);
            $result .= "$accountBalanceReport->_year: ";
            $result .= GoodBadValueViewHelper::execute($value, line: 0.15, moreBetter: true);
            $result .= '<br>';
        }

        return $result;
    }

    public static function getMathMLFormula(): string
    {
        return '<math xmlns="http://www.w3.org/1998/Math/MathML" class="math-large">
          <mfrac>
            <mrow>
              <mi>Капитал (490 строка ОББ)</mi>
              <mo>+</mo>
              <mi>Долгосрочные обязательства (590 строка ОББ)</mi>
              <mo>-</mo>
              <mi>Долгосрочные активы (190 строка ОББ)</mi>
            </mrow>
            <mrow>
              <mi>Краткосрочные активы (290 строка ОББ)</mi>
            </mrow>
          </mfrac>
        </math>';
    }
}