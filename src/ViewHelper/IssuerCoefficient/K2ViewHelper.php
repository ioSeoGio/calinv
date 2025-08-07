<?php

namespace src\ViewHelper\IssuerCoefficient;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\K2Calculator;
use src\ViewHelper\Tools\ShowMoreContainer;

class K2ViewHelper
{
    public static function render(Issuer $issuer, int $max = 5): string
    {
        $values = [];
        foreach ($issuer->accountBalanceReports as $accountBalanceReport) {
            $value = K2Calculator::calculate($accountBalanceReport);
            $printValue = "$accountBalanceReport->_year: ";
            $printValue .= GoodBadValueViewHelper::asBadge(
                value: $value,
                line: 0.15,
                moreBetter: true
            );
            $printValue .= '<br>';
            $values[] = $printValue;
        }

        return ShowMoreContainer::render($values);
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