<?php

namespace src\ViewHelper\ExpressRating;

use lib\FrontendHelper\GoodBadValueViewHelper;
use lib\FrontendHelper\SimpleNumberFormatter;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\DECalculator;
use src\IssuerRatingCalculator\ExpressRating\ExpressRatingCalculator;
use src\IssuerRatingCalculator\K1Calculator;
use src\IssuerRatingCalculator\K2Calculator;
use src\ViewHelper\Tools\Badge;
use yii\helpers\Html;

class ExpressRatingViewHelper
{
    public static function render(Issuer $issuer, bool $simple): string
    {
        $result = '';

        foreach ($issuer->accountBalanceReports as $accountBalanceReport) {
            $value = $simple
                ? ExpressRatingCalculator::calculateSimple($accountBalanceReport)
                : ExpressRatingCalculator::calculate($accountBalanceReport);

            $result .= $accountBalanceReport->_year . ': ';
            if ($value < 3) {
                $result .= Badge::danger(SimpleNumberFormatter::toView($value, 1));
            } elseif ($value < 6) {
                $result .= Badge::warning(SimpleNumberFormatter::toView($value, 1));
            } else {
                $result .= Badge::success(SimpleNumberFormatter::toView($value, 1));
            }

            $result .= '<br>';
        }
        return $result;
    }
}
