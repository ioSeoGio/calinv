<?php

namespace src\ViewHelper;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\PSCalculator;

class PSViewHelper
{
    public static function render(Issuer $model): string
    {
        $result = '';

        foreach ($model->profitLossReports as $profitLossReport) {
            $value = PSCalculator::calculate($model, $profitLossReport);

            $result .= "$profitLossReport->_year: ";
            $result .= $value
                ? GoodBadValueViewHelper::inRange($value, min: 0, max: 2)
                : null;
            $result .= '<br>';
        }

        return $result;
    }
}