<?php

namespace lib\Helper;

class DateTimeHelper
{
    public static function createFromShit(string $format, string $shit): ?\DateTimeImmutable
    {
        $shit = preg_replace('/[^0-9,.]/', '', $shit);
        return \DateTimeImmutable::createFromFormat($format, $shit) ?: null;
    }
}