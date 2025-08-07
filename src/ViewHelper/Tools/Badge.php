<?php

namespace src\ViewHelper\Tools;

use yii\helpers\Html;

class Badge
{
    public static function success(mixed $content): string
    {
        return self::render('bg-success', $content);
    }

    public static function warning(mixed $content): string
    {
        return self::render('bg-warning', $content);
    }

    public static function danger(mixed $content): string
    {
        return self::render('bg-danger', $content);
    }

    public static function neutral(mixed $content): string
    {
        return self::render('bg-secondary', $content);
    }

    private static function render(string $class, mixed $content): string
    {
        return Html::tag('strong', $content, [
            'class' => "badge $class text-dark",
        ]);
    }
}