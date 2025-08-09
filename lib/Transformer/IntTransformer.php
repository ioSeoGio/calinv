<?php

namespace lib\Transformer;

use NumberFormatter;

class IntTransformer
{
    public static function fromShit(mixed $shit): int
    {
        $formatter = new NumberFormatter('en_US', NumberFormatter::TYPE_INT64);
        return $formatter->parse($shit);
    }
}