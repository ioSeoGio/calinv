<?php

namespace src\ViewHelper;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\POCFCalculator;

class POCFViewHelper
{
    public static function render(Issuer $model): string
    {
        $result = '';

        foreach ($model->cashFlowReports as $cashFlowReport) {
            $value = POCFCalculator::calculate($model, $cashFlowReport);

            $result .= "$cashFlowReport->_year: ";
            $result .= $value
                ? GoodBadValueViewHelper::inRange($value, min: 0, max: 20)
                : null;
            $result .= '<br>';
        }

        return $result;
    }
}