<?php

namespace src\Integration\FinanceReport\Dto;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class FinanceReportAccountingBalanceDto
{
    public bool $isMock = false;

    #[SerializedPath('[error]')]
    public ?string $error = null;

    #[Assert\NotBlank]
    #[SerializedPath('[year]')]
    public ?string $year = null;

    #[SerializedPath('[balance][110_actual]')]
    public ?float $_110 = null;

    #[SerializedPath('[balance][120_actual]')]
    public ?float $_120 = null;

    #[SerializedPath('[balance][130_actual]')]
    public ?float $_130 = null;

    #[SerializedPath('[balance][131_actual]')]
    public ?float $_131 = null;

    #[SerializedPath('[balance][140_actual]')]
    public ?float $_140 = null;

    #[SerializedPath('[balance][150_actual]')]
    public ?float $_150 = null;

    #[SerializedPath('[balance][160_actual]')]
    public ?float $_160 = null;

    #[SerializedPath('[balance][170_actual]')]
    public ?float $_170 = null;

    #[SerializedPath('[balance][180_actual]')]
    public ?float $_180 = null;

    /** Долгосрочные активы */
    #[Assert\NotBlank]
    #[SerializedPath('[balance][190_actual]')]
    public ?float $_190 = null;

    #[SerializedPath('[balance][210_actual]')]
    public ?float $_210 = null;

    #[SerializedPath('[balance][211_actual]')]
    public ?float $_211 = null;

    #[SerializedPath('[balance][213_actual]')]
    public ?float $_213 = null;

    #[SerializedPath('[balance][214_actual]')]
    public ?float $_214 = null;

    #[SerializedPath('[balance][215_actual]')]
    public ?float $_215 = null;

    #[SerializedPath('[balance][230_actual]')]
    public ?float $_230 = null;

    #[SerializedPath('[balance][240_actual]')]
    public ?float $_240 = null;

    #[SerializedPath('[balance][250_actual]')]
    public ?float $_250 = null;

    #[SerializedPath('[balance][260_actual]')]
    public ?float $_260 = null;

    #[SerializedPath('[balance][270_actual]')]
    public ?float $_270 = null;

    #[SerializedPath('[balance][280_actual]')]
    public ?float $_280 = null;

    /** Краткосрочные активы */
    #[Assert\NotBlank]
    #[SerializedPath('[balance][290_actual]')]
    public ?float $_290 = null;

    #[Assert\NotBlank]
    #[SerializedPath('[balance][300_actual]')]
    public ?float $_300 = null;

    #[SerializedPath('[balance][410]')]
    public ?float $_410 = null;

    #[SerializedPath('[balance][440]')]
    public ?float $_440 = null;

    #[SerializedPath('[balance][450]')]
    public ?float $_450 = null;

    #[SerializedPath('[balance][460]')]
    public ?float $_460 = null;

    /** Капитал */
    #[Assert\NotBlank]
    #[SerializedPath('[balance][490_actual]')]
    public ?float $_490 = null;

    #[SerializedPath('[balance][510_actual]')]
    public ?float $_510 = null;

    #[SerializedPath('[balance][540_actual]')]
    public ?float $_540 = null;

    /** Долгосрочные обязательства */
    #[Assert\NotBlank]
    #[SerializedPath('[balance][590_actual]')]
    public ?float $_590 = null;

    #[SerializedPath('[balance][610_actual]')]
    public ?float $_610 = null;

    #[SerializedPath('[balance][620_actual]')]
    public ?float $_620 = null;

    #[SerializedPath('[balance][630_actual]')]
    public ?float $_630 = null;

    #[SerializedPath('[balance][631_actual]')]
    public ?float $_631 = null;

    #[SerializedPath('[balance][632_actual]')]
    public ?float $_632 = null;

    #[SerializedPath('[balance][633_actual]')]
    public ?float $_633 = null;

    #[SerializedPath('[balance][634_actual]')]
    public ?float $_634 = null;

    #[SerializedPath('[balance][635_actual]')]
    public ?float $_635 = null;

    #[SerializedPath('[balance][636_actual]')]
    public ?float $_636 = null;

    #[SerializedPath('[balance][637_actual]')]
    public ?float $_637 = null;

    #[SerializedPath('[balance][638_actual]')]
    public ?float $_638 = null;

    #[SerializedPath('[balance][650_actual]')]
    public ?float $_650 = null;

    #[SerializedPath('[balance][670_actual]')]
    public ?float $_670 = null;

    /** Краткосрочные обязательства */
    #[Assert\NotBlank]
    #[SerializedPath('[balance][690_actual]')]
    public ?float $_690 = null;

    #[Assert\NotBlank]
    #[SerializedPath('[balance][700_actual]')]
    public ?float $_700 = null;

    #[SerializedPath('[balance][110_last]')]
    public ?float $last_110 = null;

    #[SerializedPath('[balance][120_last]')]
    public ?float $last_120 = null;

    #[SerializedPath('[balance][130_last]')]
    public ?float $last_130 = null;

    #[SerializedPath('[balance][131_last]')]
    public ?float $last_131 = null;

    #[SerializedPath('[balance][140_last]')]
    public ?float $last_140 = null;

    #[SerializedPath('[balance][150_last]')]
    public ?float $last_150 = null;

    #[SerializedPath('[balance][160_last]')]
    public ?float $last_160 = null;

    #[SerializedPath('[balance][170_last]')]
    public ?float $last_170 = null;

    #[SerializedPath('[balance][180_last]')]
    public ?float $last_180 = null;

    /** Долгосрочные активы */
    #[Assert\NotBlank]
    #[SerializedPath('[balance][190_last]')]
    public ?float $last_190 = null;

    #[SerializedPath('[balance][210_last]')]
    public ?float $last_210 = null;

    #[SerializedPath('[balance][211_last]')]
    public ?float $last_211 = null;

    #[SerializedPath('[balance][213_last]')]
    public ?float $last_213 = null;

    #[SerializedPath('[balance][214_last]')]
    public ?float $last_214 = null;

    #[SerializedPath('[balance][215_last]')]
    public ?float $last_215 = null;

    #[SerializedPath('[balance][230_last]')]
    public ?float $last_230 = null;

    #[SerializedPath('[balance][240_last]')]
    public ?float $last_240 = null;

    #[SerializedPath('[balance][250_last]')]
    public ?float $last_250 = null;

    #[SerializedPath('[balance][260_last]')]
    public ?float $last_260 = null;

    #[SerializedPath('[balance][270_last]')]
    public ?float $last_270 = null;

    #[SerializedPath('[balance][280_last]')]
    public ?float $last_280 = null;

    /** Краткосрочные активы */
    #[Assert\NotBlank]
    #[SerializedPath('[balance][290_last]')]
    public ?float $last_290 = null;

    #[Assert\NotBlank]
    #[SerializedPath('[balance][300_last]')]
    public ?float $last_300 = null;

    #[SerializedPath('[balance][410_last]')]
    public ?float $last_410 = null;

    #[SerializedPath('[balance][440_last]')]
    public ?float $last_440 = null;

    #[SerializedPath('[balance][450_last]')]
    public ?float $last_450 = null;

    #[SerializedPath('[balance][460_last]')]
    public ?float $last_460 = null;

    /** Капитал */
    #[Assert\NotBlank]
    #[SerializedPath('[balance][490_last]')]
    public ?float $last_490 = null;

    #[SerializedPath('[balance][510_last]')]
    public ?float $last_510 = null;

    #[SerializedPath('[balance][540_last]')]
    public ?float $last_540 = null;

    /** Долгосрочные обязательства */
    #[Assert\NotBlank]
    #[SerializedPath('[balance][590_last]')]
    public ?float $last_590 = null;

    #[SerializedPath('[balance][610_last]')]
    public ?float $last_610 = null;

    #[SerializedPath('[balance][620_last]')]
    public ?float $last_620 = null;

    #[SerializedPath('[balance][630_last]')]
    public ?float $last_630 = null;

    #[SerializedPath('[balance][631_last]')]
    public ?float $last_631 = null;

    #[SerializedPath('[balance][632_last]')]
    public ?float $last_632 = null;

    #[SerializedPath('[balance][633_last]')]
    public ?float $last_633 = null;

    #[SerializedPath('[balance][634_last]')]
    public ?float $last_634 = null;

    #[SerializedPath('[balance][635_last]')]
    public ?float $last_635 = null;

    #[SerializedPath('[balance][636_last]')]
    public ?float $last_636 = null;

    #[SerializedPath('[balance][637_last]')]
    public ?float $last_637 = null;

    #[SerializedPath('[balance][638_last]')]
    public ?float $last_638 = null;

    #[SerializedPath('[balance][650_last]')]
    public ?float $last_650 = null;

    #[SerializedPath('[balance][670_last]')]
    public ?float $last_670 = null;

    /** Краткосрочные обязательства */
    #[Assert\NotBlank]
    #[SerializedPath('[balance][690_last]')]
    public ?float $last_690 = null;

    #[Assert\NotBlank]
    #[SerializedPath('[balance][700_last]')]
    public ?float $last_700 = null;
}