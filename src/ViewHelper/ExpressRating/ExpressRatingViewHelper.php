<?php

namespace src\ViewHelper\ExpressRating;

use lib\FrontendHelper\SimpleNumberFormatter;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\ExpressRating\ExpressRatingCalculator;
use src\ViewHelper\Tools\Badge;
use src\ViewHelper\Tools\ShowMoreContainer;

class ExpressRatingViewHelper
{
    public static function render(Issuer $issuer, bool $simple, int $max = 5): string
    {
        $values = [];
        foreach ($issuer->accountBalanceReports as $accountBalanceReport) {
            $value = $simple
                ? ExpressRatingCalculator::calculateSimple($accountBalanceReport)
                : ExpressRatingCalculator::calculate($accountBalanceReport);

            $printValue = $accountBalanceReport->_year . ': ';
            if ($value < 3) {
                $printValue .= Badge::danger(SimpleNumberFormatter::toView($value, 1));
            } elseif ($value < 6) {
                $printValue .= Badge::warning(SimpleNumberFormatter::toView($value, 1));
            } else {
                $printValue .= Badge::success(SimpleNumberFormatter::toView($value, 1));
            }
            $printValue .= '<br>';

            $values[] = $printValue;
        }

        return ShowMoreContainer::render($values, $max);
    }
}
