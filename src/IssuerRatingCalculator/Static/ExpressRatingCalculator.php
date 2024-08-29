<?php

namespace src\IssuerRatingCalculator\Static;

use app\models\IssuerRating\IssuerRating;

class ExpressRatingCalculator
{
    public static function calculate(IssuerRating $issuerRating): array
    {
        $rating = [];
        foreach ($issuerRating->indicator as $indicator) {
            $first = ($indicator['longLiability'] + $indicator['shortLiability'])
                / ($indicator['longAsset'] + $indicator['shortAsset']);
            $second = $indicator['shortAsset'] / ($indicator['shortLiability'] ?: 1);

            $mark = 0;
            if ($first >= 0.89) {
                $mark += 1;
            } elseif ($first >= 0.7) {
                $mark += 2;
            } elseif ($first >= 0.53) {
                $mark += 3;
            } elseif ($first >= 0.31) {
                $mark += 4;
            } else {
                $mark += 5;
            }

            if ($second >= 3.07) {
                $mark += 5;
            } elseif ($second >= 2.10) {
                $mark += 4;
            } elseif ($second >= 1.53) {
                $mark += 3;
            } elseif ($second >= 1.2) {
                $mark += 2;
            } else {
                $mark += 1;
            }

            $rating[] = $mark;
        }

        return $rating;
    }
}
