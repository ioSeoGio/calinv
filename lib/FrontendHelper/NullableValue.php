<?php

namespace lib\FrontendHelper;

use yii\helpers\Html;

class NullableValue
{
    public static function print(mixed $value): string
    {
        return $value ?: Html::tag('span', '(не задано)', ['class' => 'not-set']);
    }
}