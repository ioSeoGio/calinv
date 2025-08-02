<?php

namespace src\ViewHelper\Issuer\Share;

use lib\FrontendHelper\Icon;
use src\Entity\Issuer\Issuer;
use Yii;
use yii\helpers\Html;

class IssuerShareInfoModeratedIconPrinter
{
    public static function print(Issuer $issuer): string
    {
        if ($issuer->dateShareInfoModerated) {
            return Html::tag(
                'span',
                Icon::print('bi bi-piggy-bank-fill'),
                [
                    'class' => 'btn btn-sm btn-outline-success m-1',
                    'title' => 'Информация о акциях эмитента была проверена вручную модератором '
                        . "(" . Yii::$app->formatter->asRelativeTime($issuer->dateShareInfoModerated) . ")",
                ]
            );
        }

        return Html::tag(
            'span',
            Icon::print('bi bi-piggy-bank'),
            [
                'class' => 'btn btn-sm btn-outline-danger m-1',
                'title' => 'Информация о акциях эмитента не подтверждена модератором, расчеты коэффициентов и капитала могут быть неточными',
            ]
        );
    }
}