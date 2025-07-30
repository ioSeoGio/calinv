<?php

namespace src\ViewHelper;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\PFCFCalculator;

class PFCFViewHelper
{
    public static function render(Issuer $model): string
    {
        $result = '';

        foreach ($model->cashFlowReports as $cashFlowReport) {
            $value = PFCFCalculator::calculate($model, $cashFlowReport);

            $result .= "$cashFlowReport->_year: ";
            $result .= $value
                ? GoodBadValueViewHelper::inRange($value, min: 10, max: 20)
                : null;
            $result .= '<br>';
        }

        return $result;
    }
}