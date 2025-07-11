<?php

namespace src\ViewHelper;

use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\PBCalculator;
use src\IssuerRatingCalculator\PCFCalculator;

class PCFViewHelper
{
    public static function render(Issuer $model): string
    {
        $result = '';

        foreach ($model->cashFlowReports as $cashFlowReport) {
            $value = PCFCalculator::calculate($model, $cashFlowReport);

            $result .= "$cashFlowReport->_year: ";
            $result .= $value
                ? GoodBadValueViewHelper::inRange($value, min: 10, max: 20)
                : null;
            $result .= '<br>';
        }

        return $result;
    }
}