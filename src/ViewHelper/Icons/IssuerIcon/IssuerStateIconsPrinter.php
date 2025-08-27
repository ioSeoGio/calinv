<?php

namespace src\ViewHelper\Icons\IssuerIcon;

use src\Entity\Issuer\Issuer;
use src\ViewHelper\Icons\IssuerIcon\Share\IssuerShareFullnessStateIconPrinter;
use src\ViewHelper\Icons\IssuerIcon\Share\IssuerShareInfoModeratedIconPrinter;

class IssuerStateIconsPrinter
{
    public static function printMany(Issuer $model): string
    {
        $result = '';

        $result .= ''
            . IssuerEmployeeRetiredMoreThanAllowedPercentIconPrinter::print($model)
            . IssuerDebtIconPrinter::print($model)
            . IssuerTaxesDebtIconPrinter::print($model)
            . IssuerBankruptOrLiquidationIconPrinter::print($model)
            . IssuerShareFullnessStateIconPrinter::print($model)
            . IssuerShareInfoModeratedIconPrinter::print($model)
            . IssuerUnreliableSupplierIconPrinter::print($model);

        return $result;
    }
}