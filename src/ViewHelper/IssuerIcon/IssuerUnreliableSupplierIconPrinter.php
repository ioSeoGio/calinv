<?php

namespace src\ViewHelper\IssuerIcon;

use lib\FrontendHelper\Icon;
use src\Entity\Issuer\Issuer;
use yii\helpers\Html;

class IssuerUnreliableSupplierIconPrinter
{
    public static function print(Issuer $issuer): string
    {
        if ($issuer->unreliableSupplier === null) {
            return '';
        }

        return Html::tag(
            'span',
            Icon::print('bi bi-cart-x'),
            [
                'class' => 'btn btn-sm btn-outline-danger me-1',
                'title' => 'Эмитент состоит в реестре недобросовестных поставщиков',
            ]
        );
    }
}