<?php

namespace src\ViewHelper;

use yii\bootstrap5\Html;

class GoodBadValueViewHelper
{
    public static function execute(
        int|float $value,
        int|float $line,
        int $decimals = 2,
        bool $moreBetter = true,
        bool $withCurrency = false,
        string $postfix = '',
    ): string {
        if ($moreBetter) {
            $class = $value > $line ? 'text-success' : 'text-danger';
        } else {
            $class = $value < $line ? 'text-success' : 'text-danger';
        }

        return Html::tag(
            name: 'span',
            content: SimpleNumberFormatter::toView($value, decimals: $decimals, withCurrency: $withCurrency, postfix: $postfix),
            options: ['class' => $class]
        );
    }

    public static function inRange(
        int|float $value,
        int|float $min,
        int|float $max,
        int $decimals = 2,
        bool $withCurrency = false,
        string $postfix = '',
    ): string {
        $class = $value >= $min && $value <= $max ? 'text-success' : 'text-danger';

        return Html::tag(
            name: 'span',
            content: SimpleNumberFormatter::toView($value, decimals: $decimals, withCurrency: $withCurrency, postfix: $postfix),
            options: ['class' => $class]
        );
    }

    public static function asBool(bool $value, bool $trueIsGood = true): string
    {
        if ($trueIsGood) {
            $class = $value ? 'text-success' : 'text-danger';
        } else {
            $class = $value ? 'text-danger' : 'text-success';
        }

        return Html::tag(
            name: 'span',
            content: $value ? 'Да' : 'Нет',
            options: ['class' => $class]
        );
    }
}
