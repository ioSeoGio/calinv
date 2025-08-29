<?php

namespace lib\Telegram\Helper;

class TelegramDiffPrinter
{
    public function getChange(float $newValue, ?float $oldValue): string
    {
        if ($oldValue === null) {
            return '';
        }

        $difference = $newValue - $oldValue;
        $percentChange = round($difference / $oldValue * 100, 2);

        if ($difference == 0) {
            return '';
        }

        if ($difference > 0) {
            return "(*+$difference*, *+$percentChange%* 🟢)";
        }

        return "(*$difference*, *$percentChange%* 🔴)";
    }
}