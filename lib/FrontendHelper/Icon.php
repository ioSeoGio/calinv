<?php

namespace lib\FrontendHelper;

use yii\helpers\Html;

class Icon
{
    public static function print(string $icon): string
    {
        return Html::tag('i', '', ['class' => $icon]);
    }
}