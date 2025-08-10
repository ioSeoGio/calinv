<?php

namespace src\IssuerRatingCalculator\ComplexRating;

use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;
use src\Entity\Issuer\FinanceReport\CashFlowReport\CashFlowReport;
use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReport;
use src\Entity\Issuer\Issuer;
use src\IssuerRatingCalculator\DECalculator;
use src\IssuerRatingCalculator\K1Calculator;
use src\IssuerRatingCalculator\K2Calculator;
use src\IssuerRatingCalculator\K3Calculator;
use src\IssuerRatingCalculator\PBCalculator;
use src\IssuerRatingCalculator\PECalculator;
use src\IssuerRatingCalculator\PFCFCalculator;
use src\IssuerRatingCalculator\POCFCalculator;
use src\IssuerRatingCalculator\PSCalculator;
use src\IssuerRatingCalculator\ROACalculator;
use src\IssuerRatingCalculator\ROECalculator;

class ComplexRatingCalculator
{
    public static function calculateMany(Issuer $issuer): array
    {
        $profitLossReports = $issuer->profitLossReports;
        $accountingBalanceReports = $issuer->accountBalanceReports;
        $cashFlowReports = $issuer->cashFlowReports;

        $maxAmount = min(
            count($profitLossReports),
            count($accountingBalanceReports),
            count($cashFlowReports)
        );

        $values = [];
        $index = 0;
        while ($index < $maxAmount) {
            $values[$profitLossReports[$index]->_year] = self::calculateOne(
                $issuer,
                $accountingBalanceReports[$index],
                $profitLossReports[$index],
                $cashFlowReports[$index]
            );
            $index++;
        }

        return $values;
    }

    public static function calculateOne(
        Issuer $issuer,
        AccountingBalance $accountingBalance,
        ProfitLossReport $profitLossReport,
        CashFlowReport $cashFlowReport,
    ): float {
        $pe = PECalculator::calculate($issuer, $profitLossReport);
        $pb = PBCalculator::calculate($issuer, $accountingBalance);
        $k1 = K1Calculator::calculate($accountingBalance);
        $k2 = K2Calculator::calculate($accountingBalance);
        $k3 = K3Calculator::calculate($accountingBalance);
        $roe = ROECalculator::calculate($profitLossReport, $accountingBalance);
        $roa = ROACalculator::calculate($cashFlowReport, $accountingBalance);
        $de = DECalculator::calculate($accountingBalance);
        $pocf = POCFCalculator::calculate($issuer, $cashFlowReport);
        $pfcf = PFCFCalculator::calculate($issuer, $cashFlowReport);
        $ps = PSCalculator::calculate($issuer, $profitLossReport);

        $raw = (
            0
            + self::calculateScore($pe, 0.01, 0, 30)
            + self::calculateScore($pb, 0.01, 0, 15)
            + self::calculateScore($k1, 1.7, 1.15, 3)
            + self::calculateScore($k2, 0.2, 0.05, 0.5)
            + self::calculateScore($k3, 0.7, 0.01, 1.5)
            + self::calculateScore($roe, 2.5, 0, 5)
            + self::calculateScore($roa, 2, 0, 4.5)
            + self::calculateScore($de, 0.01, 0, 2)
            + self::calculateScore($pocf, 0, 0, 20)
            + self::calculateScore($pfcf, 0, 0, 20)
            + self::calculateScore($ps, 0, 0, 2.5)
        ) / 11;

        return round($raw, 2);
    }

    private static function calculateScore(
        float|int $value,
        float|int $ideal,
        float|int $min,
        float|int $max,
    ): float {
        // Проверяем, находится ли value вне границ min и max
        if ($value < $min || $value > $max) {
            return 0;
        }

        // Вычисляем максимальное отклонение от идеала
        $maxDeviation = max(abs($ideal - $min), abs($ideal - $max));

        // Вычисляем текущее отклонение от идеала
        $currentDeviation = abs($ideal - $value);

        // Вычисляем балл по линейной зависимости
        $score = 10 * (1 - $currentDeviation / $maxDeviation);

        // Округляем до 2 знаков после запятой
        return round($score, 2);
    }
}