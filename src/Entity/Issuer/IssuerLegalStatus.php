<?php

namespace src\Entity\Issuer;

enum IssuerLegalStatus: string
{
    case active = 'Действующий';
    case liquidation = 'В стадии ликвидации';
    case bankrupt = 'Процедура банкротства';
    case unknown = 'Неизвестный';

    public function isFilled(): bool
    {
        return $this !== self::unknown;
    }
}