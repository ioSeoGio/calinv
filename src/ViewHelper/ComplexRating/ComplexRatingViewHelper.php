<?php

namespace src\ViewHelper\ComplexRating;

use lib\FrontendHelper\SimpleNumberFormatter;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\CapitalizationByShareCalculator;
use src\IssuerRatingCalculator\ComplexRating\ComplexRatingCalculator;
use src\ViewHelper\Tools\Badge;
use src\ViewHelper\Tools\ShowMoreContainer;

class ComplexRatingViewHelper
{
    public static function render(Issuer $issuer, ?float $capitalization = null, int $max = 5): string
    {
        if ($capitalization === null && CapitalizationByShareCalculator::calculate($issuer) == 0) {
            return '';
        }

        $values = ComplexRatingCalculator::calculateMany($issuer, $capitalization);
        $printValues = [];

        foreach ($values as $year => $value) {
            $printValue = $year . ': ';

            if ($value < 3) {
                $printValue .= Badge::danger(SimpleNumberFormatter::toView($value, 1));
            } elseif ($value < 6) {
                $printValue .= Badge::warning(SimpleNumberFormatter::toView($value, 1));
            } else {
                $printValue .= Badge::success(SimpleNumberFormatter::toView($value, 1));
            }
            $printValue .= '<br>';

            $printValues[] = $printValue;
        }

        return ShowMoreContainer::render($printValues, $max);
    }
}
