<?php

namespace src\Entity\Issuer;

enum IssuerFullnessState: string
{
    case initial = 'initial';
    case nameAndStatus = 'nameAndStatus';
    case shares = 'shares';
    case sharesWithException = 'sharesWithException';
    case addressInfo = 'addressInfo';

    public function equals(self $state): bool
    {
        return $this->value === $state->value;
    }

    public function equalsString(string $state): bool
    {
        return $this->value === $state;
    }
}