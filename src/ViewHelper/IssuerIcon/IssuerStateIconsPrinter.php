<?php

namespace src\ViewHelper\IssuerIcon;

use lib\FrontendHelper\Icon;
use src\Entity\Issuer\Issuer;
use src\Entity\User\UserRole;
use src\ViewHelper\IssuerIcon\Share\IssuerShareFullnessStateIconPrinter;
use src\ViewHelper\IssuerIcon\Share\IssuerShareInfoModeratedIconPrinter;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

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