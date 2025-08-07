<?php

namespace src\ViewHelper\IssuerCoefficient;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\DECalculator;
use src\ViewHelper\Tools\ShowMoreContainer;

class DEViewHelper
{
    public static function render(Issuer $issuer): string
    {
        $values = [];

        foreach ($issuer->accountBalanceReports as $accountBalanceReport) {
            $value = DECalculator::calculate($accountBalanceReport);
            $result = "$accountBalanceReport->_year: ";
            $result .= GoodBadValueViewHelper::asBadge($value, line: 1, moreBetter: false);
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
              <mi>Долгосрочные обязательства (590 строка ОББ)</mi>
            </mrow>
            <mrow>
              <mi>Капитал (490 строка ОББ)</mi>
            </mrow>
          </mfrac>
        </math>';
    }
}