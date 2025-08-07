<?php

namespace src\Entity\Issuer\CreditRating;

use lib\Helper\TrimHelper;

enum CreditRating: string
{
    case AAA = 'by.AAA';
    case AAplus = 'by.AA+';
    case AA = 'by.AA';
    case Aplus = 'by.A+';
    case A = 'by.A';

    case BBBplus = 'by.BBB+';
    case BBB = 'by.BBB';
    case BBplus = 'by.BB+';
    case BB = 'by.BB';
    case Bplus = 'by.B+';
    case B = 'by.B';

    case CCC = 'by.CCC';
    case CC = 'by.CC';
    case C = 'by.C';

    case D = 'by.D';

    case Expired = 'Отозван';
    case Unknown = 'Неизвестный';

    public function isGood(): bool
    {
        return in_array($this, [
            self::AAA,
            self::AAplus,
            self::AA,
            self::Aplus,
            self::A,
        ]);
    }

    public function isOk(): bool
    {
        return in_array($this, [
            self::BBBplus,
            self::BBB,
        ]);
    }

    public function isBad(): bool
    {
        return in_array($this, [
            self::BBplus,
            self::BB,
            self::Bplus,
            self::B,
            self::CCC,
            self::CC,
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

    public static function fromString(?string $forecast): self
    {
        if ($forecast === null) {
            return self::Unknown;
        }

        $forecast = TrimHelper::trim($forecast);

        return self::tryFrom($forecast) ?: self::Unknown;
    }
}