<?php

namespace src\ViewHelper\IssuerCoefficient;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\PECalculator;

class PEViewHelper
{
    public static function render(Issuer $model): string
    {
        $result = '';

        foreach ($model->profitLossReports as $profitLossReport) {
            $pe = PECalculator::calculate($model, $profitLossReport);

            $result .= "$profitLossReport->_year: ";
            $result .= $pe
                ? GoodBadValueViewHelper::execute($pe, line: 10, moreBetter: false)
                : null;
            $result .= '<br>';
        }

        return $result;
    }
}