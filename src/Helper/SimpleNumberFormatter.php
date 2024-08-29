<?php

namespace src\Helper;

class SimpleNumberFormatter
{
    public static function toView(float|int $value, int $decimals = 2, bool $withCurrency = false): mixed
    {
        $result = number_format($value, $decimals, ',', '.');

        if ($withCurrency) {
            $result .= ' руб.';
        }

        return $result;
    }
}
