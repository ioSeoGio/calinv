<?php

namespace src\IssuerRatingCalculator\ExpressRating;

class ExpressRatingCalculator
{
    public static function calculateSimple(float $k1, float $k2): float
    {
        // Расчет баллов для первого коэффициента
        if ($k1 >= 0.89) {
            $score1 = 1.0;
        } elseif ($k1 >= 0.7) {
            $score1 = 2.0;
        } elseif ($k1 >= 0.53) {
            $score1 = 3.0;
        } elseif ($k1 >= 0.31) {
            $score1 = 4.0 ;
        } else {
            $score1 = 5.0;
        }

        // Расчет баллов для второго коэффициента
        if ($k2 <= 1.19) {
            $score2 = 1.0;
        } elseif ($k2 <= 1.52) {
            $score2 = 2.0;
        } elseif ($k2 <= 2.09) {
            $score2 = 3.0;
        } elseif ($k2 <= 3.06) {
            $score2 = 4.0;
        } else {
            $score2 = 5.0;
        }

        // Суммирование баллов и приведение к диапазону 0.1-10.0
        $totalScore = $score1 + $score2;
        $totalScore = max(0.1, min(10.0, $totalScore));

        // Округление до одного знака после запятой
        return round($totalScore, 1);
    }

    public static function calculate(float $k1, float $k2): float
    {
        // Расчет баллов для первого коэффициента
        if ($k1 >= 0.89) {
            $score1 = 1.0;
        } elseif ($k1 >= 0.7) {
            // Линейная интерполяция между 1.0 и 2.0
            $score1 = 2.0 - ($k1 - 0.7) * (1.0 / (0.89 - 0.7));
        } elseif ($k1 >= 0.53) {
            // Линейная интерполяция между 2.0 и 3.0
            $score1 = 3.0 - ($k1 - 0.53) * (1.0 / (0.69 - 0.53));
        } elseif ($k1 >= 0.31) {
            // Линейная интерполяция между 3.0 и 4.0
            $score1 = 4.0 - ($k1 - 0.31) * (1.0 / (0.52 - 0.31));
        } else {
            // Линейная интерполяция между 4.0 и 5.0 для k1 <= 0.3
            $score1 = 5.0 - max(0, $k1) * (1.0 / 0.3);
        }

        // Расчет баллов для второго коэффициента
        if ($k2 <= 1.19) {
            $score2 = 1.0;
        } elseif ($k2 <= 1.52) {
            // Линейная интерполяция между 1.0 и 2.0
            $score2 = 1.0 + ($k2 - 1.19) * (1.0 / (1.52 - 1.19));
        } elseif ($k2 <= 2.09) {
            // Линейная интерполяция между 2.0 и 3.0
            $score2 = 2.0 + ($k2 - 1.53) * (1.0 / (2.09 - 1.53));
        } elseif ($k2 <= 3.06) {
            // Линейная интерполяция между 3.0 и 4.0
            $score2 = 3.0 + ($k2 - 2.10) * (1.0 / (3.06 - 2.10));
        } else {
            // Линейная интерполяция между 4.0 и 5.0
            $score2 = 4.0 + min(1, ($k2 - 3.07) / 1) * 1.0;
        }

        // Суммирование баллов и приведение к диапазону 0.1-10.0
        $totalScore = $score1 + $score2;
        $totalScore = max(0.1, min(10.0, $totalScore));

        // Округление до одного знака после запятой
        return round($totalScore, 1);
    }
}
