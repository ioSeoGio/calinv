<?php

namespace lib\Helper;

class TrimHelper
{
    public static function trim(string $string): string
    {
        return trim(str_replace(["\n", "\r", "\t"], '', $string));
    }
}