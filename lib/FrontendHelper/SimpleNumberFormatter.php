<?php

namespace lib\FrontendHelper;

class SimpleNumberFormatter
{
    public static function toView(float|int $value, int $decimals = 2, bool $withCurrency = false, string $postfix = ''): mixed
    {
        $result = number_format($value, $decimals, ',', '.');

        if ($withCurrency && empty($postfix)) {
            $result .= ' руб.';
        }

        if (!empty($postfix)) {
            $result .= ' ' . $postfix;
        }

        return $result;
    }
}
