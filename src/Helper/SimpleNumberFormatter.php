<?php

namespace src\Helper;

class SimpleNumberFormatter
{
    public static function toView(float|int $value, int $decimals = 2): mixed
    {
        return number_format($value, $decimals, ',', '.');
    }
}
