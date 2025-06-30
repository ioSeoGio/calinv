<?php

namespace src\Integration\Bik;

use src\Entity\Issuer\BusinessReputationRating\IssuerBusinessReputation;

enum BikBusinessReputation: string
{
    case AAA = 'AAA reputation';
    case AA = 'AA reputation';
    case A = 'A reputation';
    case B = 'B reputation';
    case C = 'C reputation';
    case D = 'D reputation';
    case Expired = 'Отозван';
    case Unknown = 'Неизвестный';

    public static function fromString(string $string): self
    {
        return self::tryFrom($string) ?: self::Unknown;
    }

    public function toIssuerBusinessReputation(): IssuerBusinessReputation
    {
        return IssuerBusinessReputation::from($this->value);
    }
}