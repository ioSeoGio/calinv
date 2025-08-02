<?php

namespace src\ViewHelper\Issuer;

use lib\FrontendHelper\Icon;
use src\Entity\Issuer\Issuer;
use yii\helpers\Html;

class IssuerBankruptOrLiquidationIconPrinter
{
    public static function print(Issuer $issuer): string
    {
        if (!$issuer->additionalInfo?->isBankrupting && $issuer->liquidationInfo === null) {
            return '';
        }

        return Html::tag(
            'span',
            Icon::print('bi bi-x-octagon-fill'),
            [
                'class' => 'btn btn-sm btn-outline-danger m-1',
                'title' => 'Эмитент банкрот/ликвидирован или находится в процессе ликвидации',
            ]
        );
    }
}