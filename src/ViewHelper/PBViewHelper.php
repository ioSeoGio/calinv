<?php

namespace src\ViewHelper;

use lib\FrontendHelper\GoodBadValueViewHelper;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\PBCalculator;

class PBViewHelper
{
    public static function render(Issuer $model): string
    {
        $result = '';

        foreach ($model->accountBalanceReports as $accountBalanceReport) {
            $pb = PBCalculator::calculate($model, $accountBalanceReport);

            $result .= "$accountBalanceReport->_year: ";
            $result .= $pb
                ? GoodBadValueViewHelper::execute($pb, line: 1, moreBetter: false)
                : null;
            $result .= '<br>';
        }

        return $result;
    }
}