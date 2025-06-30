<?php

namespace src\Integration\CentralDepo;

use src\Entity\Issuer\IssuerLegalStatus;

enum CentralDepoLegalStatus: string
{
    case active = 'Действующий';
    case unknown = 'Неизвестный';

    public function toLegalStatus(): IssuerLegalStatus
    {
        return match ($this) {
            self::active => IssuerLegalStatus::active,
            default => IssuerLegalStatus::unknown,
        };
    }

    public static function makeFrom(mixed $value): self
    {
        return match ($value) {
            self::active->value => self::active,
            default => self::unknown,
        };
    }
}