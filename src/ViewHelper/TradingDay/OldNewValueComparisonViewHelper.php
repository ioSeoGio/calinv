<?php

namespace src\ViewHelper\TradingDay;

use lib\FrontendHelper\Icon;
use supplyhog\ClipboardJs\ClipboardJsWidget;

class OldNewValueComparisonViewHelper
{
    public static function render(float $currentValue, ?float $oldValue): string
    {
        if ($oldValue === null || $currentValue === $oldValue) {
            return '';
        }

        $difference = $currentValue - $oldValue;
        $percentDifference = round($difference / $oldValue * 100, 2) . '%';
        $percentDifference = $difference > 0 ? '+' . $percentDifference : $percentDifference;

        if ($currentValue > $oldValue) {
            $text = Icon::print('bi bi-arrow-up') . $difference . " ($percentDifference)";
            $class = 'btn btn-sm btn-outline-success me-1';
        } else {
            $text = Icon::print('bi bi-arrow-down') . $difference . " ($percentDifference)";
            $class = 'btn btn-sm btn-outline-danger me-1';
        }

        return ClipboardJsWidget::widget([
            'text' => $difference,
            'tag' => 'span',
            'htmlOptions' => ['class' => $class, 'type' => 'span', 'title' => ''],
            'label' => $text,
            'successText' => "<b>$text</b>",
        ]);
    }
}
