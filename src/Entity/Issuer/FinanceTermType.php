<?php

namespace src\Entity\Issuer;

enum FinanceTermType: string
{
    case year = 'годовой';
    case quarter1 = '1 квартал';
    case quarter2 = '2 квартал';
    case quarter3 = '3 квартал';
    case quarter4 = '4 квартал';

    public static function toFrontend(): array
    {
        return array_map(fn (self $e): string => $e->value, self::cases());
    }

    public static function toValidateRange(): array
    {
        return array_map(fn (self $e): string => $e->value, self::cases());
    }
}