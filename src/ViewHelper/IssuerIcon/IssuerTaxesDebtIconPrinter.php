<?php

namespace src\ViewHelper\IssuerIcon;

use lib\FrontendHelper\Icon;
use src\Entity\Issuer\Issuer;
use yii\helpers\Html;

class IssuerTaxesDebtIconPrinter
{
    public static function print(Issuer $issuer): string
    {
        if (empty($issuer->additionalInfo?->debtFszn) && empty($issuer->additionalInfo?->debtTaxes)) {
            return '';
        }

        return Html::tag(
            'span',
            Icon::print('bi bi-coin'),
            [
                'class' => 'btn btn-sm btn-outline-danger me-1',
                'title' => 'Эмитент имеет задолженности перед ФСЗН или МНС',
            ]
        );
    }
}