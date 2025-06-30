<?php

namespace src\Integration\Egr;

use src\Entity\Issuer\IssuerLegalStatus;

enum EgrLegalStatus: string
{
    case active = 'Действующий';

    public function toLegalStatus(): IssuerLegalStatus
    {
        return match ($this) {
            self::active => IssuerLegalStatus::active,
            default => IssuerLegalStatus::unknown,
        };
    }
}