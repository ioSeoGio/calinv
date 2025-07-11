<?php

namespace src\Integration\FinanceReport\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedPath;

class FinanceReportProfitLossDto
{
    public bool $isMock = false;

    #[SerializedPath('[error]')]
    public ?string $error = null;

    #[Assert\NotBlank]
    #[SerializedPath('[year]')]
    public ?string $year = null;

    #[SerializedPath('[profit_loss][010_actual]')]
    public ?float $_010 = null;

    #[SerializedPath('[profit_loss][020_actual]')]
    public ?float $_020 = null;

    #[SerializedPath('[profit_loss][030_actual]')]
    public ?float $_030 = null;

    #[SerializedPath('[profit_loss][040_actual]')]
    public ?float $_040 = null;

    #[SerializedPath('[profit_loss][050_actual]')]
    public ?float $_050 = null;

    #[SerializedPath('[profit_loss][060_actual]')]
    public ?float $_060 = null;

    #[SerializedPath('[profit_loss][070_actual]')]
    public ?float $_070 = null;

    #[SerializedPath('[profit_loss][080_actual]')]
    public ?float $_080 = null;

    #[SerializedPath('[profit_loss][090_actual]')]
    public ?float $_090 = null;

    #[SerializedPath('[profit_loss][100_actual]')]
    public ?float $_100 = null;

    #[SerializedPath('[profit_loss][101_actual]')]
    public ?float $_101 = null;

    #[SerializedPath('[profit_loss][102_actual]')]
    public ?float $_102 = null;

    #[SerializedPath('[profit_loss][103_actual]')]
    public ?float $_103 = null;

    #[SerializedPath('[profit_loss][104_actual]')]
    public ?float $_104 = null;

    #[SerializedPath('[profit_loss][110_actual]')]
    public ?float $_110 = null;

    #[SerializedPath('[profit_loss][111_actual]')]
    public ?float $_111 = null;

    #[SerializedPath('[profit_loss][112_actual]')]
    public ?float $_112 = null;

    #[SerializedPath('[profit_loss][120_actual]')]
    public ?float $_120 = null;

    #[SerializedPath('[profit_loss][121_actual]')]
    public ?float $_121 = null;

    #[SerializedPath('[profit_loss][122_actual]')]
    public ?float $_122 = null;

    #[SerializedPath('[profit_loss][130_actual]')]
    public ?float $_130 = null;

    #[SerializedPath('[profit_loss][131_actual]')]
    public ?float $_131 = null;

    #[SerializedPath('[profit_loss][132_actual]')]
    public ?float $_132 = null;

    #[SerializedPath('[profit_loss][133_actual]')]
    public ?float $_133 = null;

    #[SerializedPath('[profit_loss][140_actual]')]
    public ?float $_140 = null;

    #[SerializedPath('[profit_loss][150_actual]')]
    public ?float $_150 = null;

    #[SerializedPath('[profit_loss][160_actual]')]
    public ?float $_160 = null;

    #[SerializedPath('[profit_loss][170_actual]')]
    public ?float $_170 = null;

    #[SerializedPath('[profit_loss][180_actual]')]
    public ?float $_180 = null;

    #[SerializedPath('[profit_loss][190_actual]')]
    public ?float $_190 = null;

    /** Чистая прибыль (убыток) */
    #[Assert\NotBlank]
    #[SerializedPath('[profit_loss][210_actual]')]
    public ?float $_210 = null;

    #[SerializedPath('[profit_loss][220_actual]')]
    public ?float $_220 = null;

    #[SerializedPath('[profit_loss][230_actual]')]
    public ?float $_230 = null;

    /** Совокупная прибыль (убыток) */
    #[Assert\NotBlank]
    #[SerializedPath('[profit_loss][240_actual]')]
    public ?float $_240 = null;

    #[SerializedPath('[profit_loss][010_last]')]
    public ?float $last_010 = null;

    #[SerializedPath('[profit_loss][020_last]')]
    public ?float $last_020 = null;

    #[SerializedPath('[profit_loss][030_last]')]
    public ?float $last_030 = null;

    #[SerializedPath('[profit_loss][040_last]')]
    public ?float $last_040 = null;

    #[SerializedPath('[profit_loss][050_last]')]
    public ?float $last_050 = null;

    #[SerializedPath('[profit_loss][060_last]')]
    public ?float $last_060 = null;

    #[SerializedPath('[profit_loss][070_last]')]
    public ?float $last_070 = null;

    #[SerializedPath('[profit_loss][080_last]')]
    public ?float $last_080 = null;

    #[SerializedPath('[profit_loss][090_last]')]
    public ?float $last_090 = null;

    #[SerializedPath('[profit_loss][100_last]')]
    public ?float $last_100 = null;

    #[SerializedPath('[profit_loss][101_last]')]
    public ?float $last_101 = null;

    #[SerializedPath('[profit_loss][102_last]')]
    public ?float $last_102 = null;

    #[SerializedPath('[profit_loss][103_last]')]
    public ?float $last_103 = null;

    #[SerializedPath('[profit_loss][104_last]')]
    public ?float $last_104 = null;

    #[SerializedPath('[profit_loss][110_last]')]
    public ?float $last_110 = null;

    #[SerializedPath('[profit_loss][111_last]')]
    public ?float $last_111 = null;

    #[SerializedPath('[profit_loss][112_last]')]
    public ?float $last_112 = null;

    #[SerializedPath('[profit_loss][120_last]')]
    public ?float $last_120 = null;

    #[SerializedPath('[profit_loss][121_last]')]
    public ?float $last_121 = null;

    #[SerializedPath('[profit_loss][122_last]')]
    public ?float $last_122 = null;

    #[SerializedPath('[profit_loss][130_last]')]
    public ?float $last_130 = null;

    #[SerializedPath('[profit_loss][131_last]')]
    public ?float $last_131 = null;

    #[SerializedPath('[profit_loss][132_last]')]
    public ?float $last_132 = null;

    #[SerializedPath('[profit_loss][133_last]')]
    public ?float $last_133 = null;

    #[SerializedPath('[profit_loss][140_last]')]
    public ?float $last_140 = null;

    #[SerializedPath('[profit_loss][150_last]')]
    public ?float $last_150 = null;

    #[SerializedPath('[profit_loss][160_last]')]
    public ?float $last_160 = null;

    #[SerializedPath('[profit_loss][170_last]')]
    public ?float $last_170 = null;

    #[SerializedPath('[profit_loss][180_last]')]
    public ?float $last_180 = null;

    #[SerializedPath('[profit_loss][190_last]')]
    public ?float $last_190 = null;

    /** Чистая прибыль (убыток) */
    #[Assert\NotBlank]
    #[SerializedPath('[profit_loss][210_last]')]
    public ?float $last_210 = null;

    #[SerializedPath('[profit_loss][220_last]')]
    public ?float $last_220 = null;

    #[SerializedPath('[profit_loss][230_last]')]
    public ?float $last_230 = null;

    /** Совокупная прибыль (убыток) */
    #[Assert\NotBlank]
    #[SerializedPath('[profit_loss][240_last]')]
    public ?float $last_240 = null;
}