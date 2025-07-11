<?php

namespace src\IssuerRatingCalculator;

use src\Entity\Issuer\FinanceReport\CashFlowReport\CashFlowReport;
use src\Entity\Issuer\Issuer;

class PCFCalculator
{
    public static function calculate(Issuer $issuer, CashFlowReport $cashFlowReport): float
    {
        // Сейчас считаем по всем видам деятельности + за вычетом расходов
        return CapitalizationByShareCalculator::calculate($issuer) / $cashFlowReport->_110;
        // Грязное по основной деятельности
//        return CapitalizationByShareCalculator::calculate($issuer) / $cashFlowReport->_20;
    }
}