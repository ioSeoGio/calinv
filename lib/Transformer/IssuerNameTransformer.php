<?php

namespace lib\Transformer;

use lib\Helper\TrimHelper;

class IssuerNameTransformer
{
    public static function transform(string $issuerName): string
    {
        $issuerName = TrimHelper::trim($issuerName);
        $issuerName = str_replace('«', '"', $issuerName);
        $issuerName = str_replace('”', '"', $issuerName);
        $issuerName = str_replace('“', '"', $issuerName);
        $issuerName = str_replace('»', '"', $issuerName);
        return $issuerName;
    }
}