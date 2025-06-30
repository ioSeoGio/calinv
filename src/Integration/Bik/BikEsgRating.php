<?php

namespace src\Integration\Bik;

use lib\Helper\TrimHelper;
use src\Entity\Issuer\EsgRating\EsgRating;

enum BikEsgRating: string
{
    case AAA = 'AAA.esg';
    case AAPlus = 'AA+.esg';
    case AA = 'AA.esg';
    case APlus = 'A+.esg';
    case A = 'A.esg';
    case BBB = 'BBB.esg';
    case BB = 'BB.esg';
    case B = 'B.esg';
    case CCC = 'CCC.esg';
    case CC = 'CC.esg';
    case C = 'C.esg';
    case Expired = 'Отозван';
    case unknown = 'Неизвестен';

    public static function fromString(?string $rating): BikEsgRating
    {
        if ($rating === null) {
            return self::unknown;
        }

        $rating = TrimHelper::trim($rating);

        return self::tryFrom($rating) ?: self::unknown;
    }

    public function toEsgRating(): EsgRating
    {
        return EsgRating::from($this->value);
    }
}