<?php

namespace lib\FrontendHelper;

use src\ViewHelper\Tools\Badge;
use yii\bootstrap5\Html;

class GoodBadValueViewHelper
{
    public static function execute(
        int|float $value,
        int|float $line,
        bool $moreBetter,
        int $decimals = 2,
    ): string {
        if ($moreBetter) {
            $class = $value > $line ? 'text-success' : 'text-danger';
        } else {
            $class = $value < $line ? 'text-success' : 'text-danger';
        }

        if ($value == $line) {
            $class = 'text-primary';
        }

        return Html::tag(
            name: 'span',
            content: SimpleNumberFormatter::toView($value, decimals: $decimals),
            options: ['class' => $class]
        );
    }

    public static function asBadge(
        int|float $value,
        int|float $line,
        bool $moreBetter,
        string $postfix = '',
    ): string {
        $printValue = SimpleNumberFormatter::toView($value) . $postfix;

        if ($value === $line) {
            return Badge::neutral($printValue);
        }

        if ($moreBetter) {
            return $value > $line ? Badge::success($printValue) : Badge::danger($printValue);
        }

        return $value < $line ? Badge::success($printValue) : Badge::danger($printValue);
    }

    public static function inRange(
        int|float $value,
        int|float $min,
        int|float $max,
        int $decimals = 2,
    ): string {
        $class = $value >= $min && $value <= $max ? 'text-success' : 'text-danger';

        return Html::tag(
            name: 'span',
            content: SimpleNumberFormatter::toView($value, decimals: $decimals),
            options: ['class' => $class]
        );
    }

    public static function inRangeAsBadge(
        int|float $value,
        int|float $min,
        int|float $max,
    ): string {
        $printValue = SimpleNumberFormatter::toView($value);

        return $value >= $min && $value <= $max ? Badge::success($printValue) : Badge::danger($printValue);
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
