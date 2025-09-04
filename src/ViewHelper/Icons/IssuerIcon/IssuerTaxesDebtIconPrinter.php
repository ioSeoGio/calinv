<?php

namespace src\ViewHelper\Icons\IssuerIcon;

use lib\FrontendHelper\Icon;
use src\Entity\Issuer\Issuer;
use yii\helpers\Html;

class IssuerTaxesDebtIconPrinter
{
    public static function print(Issuer $issuer): string
    {
        if (!$issuer->additionalInfo?->hasTaxesDebt()) {
            return '';
        }

        return Html::tag(
            'span',
            Icon::print('bi bi-coin'),
            [
                'class' => 'btn btn-sm btn-outline-danger me-1',
                'title' => 'Эмитент имел и/или имеет задолженности перед ФСЗН или МНС, последняя задолженность: '
                    . $issuer->additionalInfo->getLatestDebtDate()->format('Y-m-d'),
            ]
        );
    }
}