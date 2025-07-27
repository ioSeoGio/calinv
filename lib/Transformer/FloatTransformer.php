<?php

namespace lib\Transformer;

use NumberFormatter;

class FloatTransformer
{
    public static function fromShitToFloat(mixed $shit): float
    {
        $formatter = new NumberFormatter('en_US', NumberFormatter::DECIMAL);
        return $formatter->parse($shit);
    }
}