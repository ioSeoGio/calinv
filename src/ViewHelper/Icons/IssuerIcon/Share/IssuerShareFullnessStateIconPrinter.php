<?php

namespace src\ViewHelper\Icons\IssuerIcon\Share;

use lib\FrontendHelper\Icon;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\IssuerFullnessState;
use yii\helpers\Html;

class IssuerShareFullnessStateIconPrinter
{
    public static function print(Issuer $issuer): string
    {
        if ($issuer->hasState(IssuerFullnessState::sharesWithException)) {
            return Html::tag(
                'span',
                Icon::print('bi bi-exclamation-octagon'),
                [
                    'class' => 'btn btn-sm btn-outline-danger me-1',
                    'title' => '
                        Информация о акциях не была получена, или получена не полностью от удаленного API. 
                        Расчеты коэффициентов и капитала могут быть неточными
                    ',
                ]
            );
        }

        return '';
    }
}