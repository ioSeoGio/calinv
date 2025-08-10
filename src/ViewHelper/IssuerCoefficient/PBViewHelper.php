<?php

namespace src\ViewHelper\IssuerCoefficient;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\PBCalculator;
use src\ViewHelper\Tools\ShowMoreContainer;

class PBViewHelper
{
    public static function render(Issuer $model, ?float $capitalizationInGrands = null): string
    {
        $values = [];

        foreach ($model->accountBalanceReports as $accountBalanceReport) {
            $pb = $capitalizationInGrands
                ? PBCalculator::calculateByCapitalization($capitalizationInGrands, $accountBalanceReport)
                : PBCalculator::calculate($model, $accountBalanceReport);

            $result = "$accountBalanceReport->_year: ";
            $result .= $pb
                ? GoodBadValueViewHelper::asBadge($pb, line: 1, moreBetter: false)
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
              <mi>Итоговый баланс (700 строка ОББ)</mi>
            </mrow>
          </mfrac>
        </math>';
    }
}