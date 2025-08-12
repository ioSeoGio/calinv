<?php

namespace src\ViewHelper\IssuerCoefficient;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\ROACalculator;
use src\IssuerRatingCalculator\ROECalculator;
use src\ViewHelper\Tools\ShowMoreContainer;

class ROAViewHelper
{
    public static function render(Issuer $issuer): string
    {
        $values = [];

        $accountBalanceReports = $issuer->accountBalanceReports;
        $profitLossReports = $issuer->profitLossReports;

        for ($i = 0; $i < min(count($accountBalanceReports), count($profitLossReports)); $i++) {
            $value = ROACalculator::calculate($profitLossReports[$i], $accountBalanceReports[$i]);
            $result = "{$profitLossReports[$i]->_year}: ";
            $result .= GoodBadValueViewHelper::asBadge($value * 100, 10,true, '%');
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
              <mi>Чистая прибыль (210 строка ОПиУ)</mi>
            </mrow>
            <mrow>
              <mi>Итого активов (700 строка ОББ)</mi>
            </mrow>
          </mfrac>
        </math>';
    }
}