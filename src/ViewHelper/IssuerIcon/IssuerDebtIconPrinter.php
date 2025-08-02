<?php

namespace src\ViewHelper\IssuerIcon;

use lib\FrontendHelper\Icon;
use src\Entity\Issuer\Issuer;
use yii\helpers\Html;

class IssuerDebtIconPrinter
{
    public static function print(Issuer $issuer): string
    {
        if (empty($issuer->additionalInfo?->orderlyCourtAmountAsDebtor)) {
            return '';
        }

        return Html::tag(
            'span',
            Icon::print('bi bi-cash-coin'),
            [
                'class' => 'btn btn-sm btn-outline-danger me-1',
                'title' => 'Эмитент судится/судился как должник',
            ]
        );
    }
}