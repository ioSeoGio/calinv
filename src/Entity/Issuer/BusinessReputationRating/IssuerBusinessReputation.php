<?php

namespace src\Entity\Issuer\BusinessReputationRating;

enum IssuerBusinessReputation: string
{
    case AAA = 'AAA reputation';
    case AA = 'AA reputation';
    case A = 'A reputation';
    case B = 'B reputation';
    case C = 'C reputation';
    case D = 'D reputation';
    case Expired = 'Отозван';
    case Unknown = 'Неизвестный';

    public function isGood(): bool
    {
        return in_array($this, [
            self::AAA,
            self::AA,
            self::A,
        ]);
    }

    public function isOk(): bool
    {
        return in_array($this, [
            self::B,
        ]);
    }

    public function isBad(): bool
    {
        return in_array($this, [
            self::C,
            self::D,
        ]);
    }

    public function isNeutral(): bool
    {
        return in_array($this, [
            self::Expired,
            self::Unknown,
        ]);
    }
}