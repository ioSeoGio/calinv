<?php

namespace src\ViewHelper\IssuerIcon;

use src\Entity\Issuer\Issuer;
use src\ViewHelper\IssuerIcon\Share\IssuerShareFullnessStateIconPrinter;
use src\ViewHelper\IssuerIcon\Share\IssuerShareInfoModeratedIconPrinter;

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