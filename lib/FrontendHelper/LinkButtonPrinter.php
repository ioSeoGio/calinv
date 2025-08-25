<?php

namespace lib\FrontendHelper;

use yii\helpers\Html;

class LinkButtonPrinter
{
    public static function printExportCsv(string $url): string
    {
        return Html::a(Html::tag(
            'span',
            "Экспортировать отчет " . Icon::printExportCsv(),
            [
                'class' => 'btn btn-sm btn-success',
                'title' => 'Экспортировать отчет в csv',
            ]
        ), $url);
    }
}