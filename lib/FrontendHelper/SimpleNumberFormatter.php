<?php

namespace lib\FrontendHelper;

class SimpleNumberFormatter
{
    public static function toView(float|int $value, int $decimals = 2): string
    {
        return number_format($value, $decimals, ',', '.');
    }

    public static function toViewWithSpaces(float|int $value, int $decimals = 2): mixed
    {
        return number_format($value, $decimals, ',', ' ');
    }
}
