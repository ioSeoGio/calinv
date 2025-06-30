<?php

namespace src\Integration\Bcse\ShareInfo;

use DateTimeImmutable;
use lib\Helper\DateTimeHelper;

class BcseShareLastDealDto
{
    public readonly \DateTimeImmutable $date;

    public function __construct(
        string $date,
        /* Цена по последней сделке */
        public readonly float $price,
        /* Изменение от предыдущей сделки в рублях */
        public readonly float $changeFromPreviousDeal,
        /* Изменение от предыдущей сделки в % */
        public readonly float $changeFromPreviousDealPercent,
    ) {
        $this->date = DateTimeHelper::createFromShit('d.m.Y', $date);
    }
}