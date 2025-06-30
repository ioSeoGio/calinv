<?php

namespace lib\Helper;

class EmptyValueChecker
{
    public static function isEmpty(mixed $value): bool
    {
        if (is_string($value)) {
            $value = str_replace(["-", "\n", "\r", "\t", " "], '', $value);
        }

        return empty($value);
    }
}