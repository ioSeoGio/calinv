<?php

namespace src\ViewHelper\Icons;

use lib\FrontendHelper\Icon;
use yii\helpers\Html;

class RisingDecreasingIcon
{
    public static function print(float $currentValue, ?float $oldValue): string
    {
        if ($oldValue === null || $currentValue === $oldValue) {
            return '';
        }

        if ($currentValue > $oldValue) {
            return Html::tag(
                'span',
                Icon::print('bi bi-arrow-up'),
                [
                    'class' => 'btn btn-sm btn-outline-success',
                ]
            );
        }

        return Html::tag(
            'span',
            Icon::print('bi bi-arrow-down'),
            [
                'class' => 'btn btn-sm btn-outline-danger',
            ]
        );
    }
}
