<?php

namespace lib\FrontendHelper;

use yii\helpers\Html;

class NullableValue
{
    public static function print(mixed $value): string
    {
        return $value ?: self::printNull();
    }

    public static function printNull(): string
    {
        return Html::tag('span', '(не задано)', ['class' => 'not-set']);
    }
}