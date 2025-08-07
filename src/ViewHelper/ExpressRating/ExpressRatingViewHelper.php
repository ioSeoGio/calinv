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
use src\ViewHelper\Tools\ShowMoreBtn;
use yii\helpers\Html;

class ExpressRatingViewHelper
{
    public static function render(Issuer $issuer, bool $simple, int $max = 5): string
    {
        $result = '';

        $count = 0;
        $hiddenValues = '';
        $id = "more-express-rating-$issuer->id-" . $simple ? 'simple' : 'complex';

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

            if ($count == $max) {
                $result .= ShowMoreBtn::renderBtn('ещё', $id);
            }

            if ($count >= $max) {
                $hiddenValues .= $printValue;
            } else {
                $result .= $printValue;
            }

            $count++;
        }

        if (!empty($hiddenValues)) {
            $result .= ShowMoreBtn::renderContainer($hiddenValues, $id);
        }

        return $result;
    }
}
