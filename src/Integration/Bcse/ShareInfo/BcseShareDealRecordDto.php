<?php

namespace src\Integration\Bcse\ShareInfo;

use lib\Helper\DateTimeHelper;
use lib\Helper\TrimHelper;
use lib\Transformer\FloatTransformer;

/**
 * Запись о совершении сделок за день на БВФБ
 */
class BcseShareDealRecordDto
{
    public \DateTimeImmutable $date;
    public string $currency;

    public float $minPrice;
    public float $maxPrice;
    /** Средневзвешенная цена за день */
    public float $weightedAveragePrice;

    /** Сумма сделок за день */
    public float $totalSum;

    /** Кол-во штук купленных за день */
    public float $totalAmount;

    /** Кол-во сделок за день */
    public float $totalDealAmount;

    public function __construct(
        string $date,
        string $currency,

        string $minPrice,
        string $maxPrice,
        string $weightedAveragePrice,

        string $totalSum,
        string $totalAmount,
        string $totalDealAmount,
    ) {
        $this->date = DateTimeHelper::createFromShit('!d.m.Y', $date);
        $this->currency = TrimHelper::trim($currency);
        $this->minPrice = FloatTransformer::fromShitToFloat($minPrice);
        $this->maxPrice = FloatTransformer::fromShitToFloat($maxPrice);
        $this->weightedAveragePrice = FloatTransformer::fromShitToFloat($weightedAveragePrice);
        $this->totalSum = FloatTransformer::fromShitToFloat($totalSum);
        $this->totalAmount = FloatTransformer::fromShitToFloat($totalAmount);
        $this->totalDealAmount = FloatTransformer::fromShitToFloat($totalDealAmount);
    }
}
