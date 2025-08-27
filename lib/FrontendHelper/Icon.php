<?php

namespace lib\FrontendHelper;

use yii\helpers\Html;

class Icon
{
    public static function print(string $icon): string
    {
        return Html::tag('i', '', ['class' => $icon]);
    }

    public static function printFaq(): string
    {
        return self::print('bi bi-question-octagon');
    }

    public static function printExportCsv(): string
    {
        return self::print('bi bi-file-earmark-arrow-down');
    }

    public static function printArrowUp(): string
    {
        return self::print('bi bi-arrow-up');
    }

    public static function printArrowDown(): string
    {
        return self::print('bi bi-arrow-down');
    }
}