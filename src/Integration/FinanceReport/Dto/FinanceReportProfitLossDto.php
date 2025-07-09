<?php

namespace src\Integration\FinanceReport\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedPath;

class FinanceReportProfitLossDto
{
    #[SerializedPath('[error]')]
    public ?string $error = null;

    #[Assert\NotBlank]
    #[SerializedPath('[year]')]
    public ?string $year = null;

    #[Assert\NotBlank]
    #[SerializedPath('[010_actual]')]
    public ?float $_010 = null;

    #[Assert\NotBlank]
    #[SerializedPath('[020_actual]')]
    public ?float $_020 = null;

    #[Assert\NotBlank]
    #[SerializedPath('[030_actual]')]
    public ?float $_030 = null;

    #[Assert\NotBlank]
    #[SerializedPath('[040_actual]')]
    public ?float $_040 = null;

    #[Assert\NotBlank]
    #[SerializedPath('[050_actual]')]
    public ?float $_050 = null;

    #[Assert\NotBlank]
    #[SerializedPath('[060_actual]')]
    public ?float $_060 = null;

    #[SerializedPath('[070_actual]')]
    public ?float $_070 = null;

    #[SerializedPath('[080_actual]')]
    public ?float $_080 = null;

    #[SerializedPath('[090_actual]')]
    public ?float $_090 = null;

    #[SerializedPath('[100_actual]')]
    public ?float $_100 = null;

    #[SerializedPath('[101_actual]')]
    public ?float $_101 = null;

    #[SerializedPath('[102_actual]')]
    public ?float $_102 = null;

    #[SerializedPath('[103_actual]')]
    public ?float $_103 = null;

    #[SerializedPath('[104_actual]')]
    public ?float $_104 = null;

    #[SerializedPath('[110_actual]')]
    public ?float $_110 = null;

    #[SerializedPath('[111_actual]')]
    public ?float $_111 = null;

    #[SerializedPath('[112_actual]')]
    public ?float $_112 = null;

    #[SerializedPath('[120_actual]')]
    public ?float $_120 = null;

    #[SerializedPath('[121_actual]')]
    public ?float $_121 = null;

    #[SerializedPath('[122_actual]')]
    public ?float $_122 = null;

    #[SerializedPath('[130_actual]')]
    public ?float $_130 = null;

    #[SerializedPath('[131_actual]')]
    public ?float $_131 = null;

    #[SerializedPath('[132_actual]')]
    public ?float $_132 = null;

    #[SerializedPath('[133_actual]')]
    public ?float $_133 = null;

    #[SerializedPath('[140_actual]')]
    public ?float $_140 = null;

    #[SerializedPath('[150_actual]')]
    public ?float $_150 = null;

    #[SerializedPath('[160_actual]')]
    public ?float $_160 = null;

    #[SerializedPath('[170_actual]')]
    public ?float $_170 = null;

    #[SerializedPath('[180_actual]')]
    public ?float $_180 = null;

    #[SerializedPath('[190_actual]')]
    public ?float $_190 = null;

    /** Чистая прибыль (убыток) */
    #[SerializedPath('[210_actual]')]
    public ?float $_210 = null;

    #[SerializedPath('[220_actual]')]
    public ?float $_220 = null;

    #[SerializedPath('[230_actual]')]
    public ?float $_230 = null;

    /** Совокупная прибыль (убыток) */
    #[Assert\NotBlank]
    #[SerializedPath('[240_actual]')]
    public ?float $_240 = null;

    #[Assert\NotBlank]
    #[SerializedPath('[010_last]')]
    public ?float $last_010 = null;

    #[Assert\NotBlank]
    #[SerializedPath('[020_last]')]
    public ?float $last_020 = null;

    #[Assert\NotBlank]
    #[SerializedPath('[030_last]')]
    public ?float $last_030 = null;

    #[Assert\NotBlank]
    #[SerializedPath('[040_last]')]
    public ?float $last_040 = null;

    #[SerializedPath('[050_last]')]
    public ?float $last_050 = null;

    #[Assert\NotBlank]
    #[SerializedPath('[060_last]')]
    public ?float $last_060 = null;

    #[SerializedPath('[070_last]')]
    public ?float $last_070 = null;

    #[SerializedPath('[080_last]')]
    public ?float $last_080 = null;

    #[SerializedPath('[090_last]')]
    public ?float $last_090 = null;

    #[SerializedPath('[100_last]')]
    public ?float $last_100 = null;

    #[SerializedPath('[101_last]')]
    public ?float $last_101 = null;

    #[SerializedPath('[102_last]')]
    public ?float $last_102 = null;

    #[SerializedPath('[103_last]')]
    public ?float $last_103 = null;

    #[SerializedPath('[104_last]')]
    public ?float $last_104 = null;

    #[SerializedPath('[110_last]')]
    public ?float $last_110 = null;

    #[SerializedPath('[111_last]')]
    public ?float $last_111 = null;

    #[SerializedPath('[112_last]')]
    public ?float $last_112 = null;

    #[SerializedPath('[120_last]')]
    public ?float $last_120 = null;

    #[SerializedPath('[121_last]')]
    public ?float $last_121 = null;

    #[SerializedPath('[122_last]')]
    public ?float $last_122 = null;

    #[SerializedPath('[130_last]')]
    public ?float $last_130 = null;

    #[SerializedPath('[131_last]')]
    public ?float $last_131 = null;

    #[SerializedPath('[132_last]')]
    public ?float $last_132 = null;

    #[SerializedPath('[133_last]')]
    public ?float $last_133 = null;

    #[SerializedPath('[140_last]')]
    public ?float $last_140 = null;

    #[SerializedPath('[150_last]')]
    public ?float $last_150 = null;

    #[SerializedPath('[160_last]')]
    public ?float $last_160 = null;

    #[SerializedPath('[170_last]')]
    public ?float $last_170 = null;

    #[SerializedPath('[180_last]')]
    public ?float $last_180 = null;

    #[SerializedPath('[190_last]')]
    public ?float $last_190 = null;

    /** Чистая прибыль (убыток) */
    #[SerializedPath('[210_last]')]
    public ?float $last_210 = null;

    #[SerializedPath('[220_last]')]
    public ?float $last_220 = null;

    #[SerializedPath('[230_last]')]
    public ?float $last_230 = null;

    /** Совокупная прибыль (убыток) */
    #[Assert\NotBlank]
    #[SerializedPath('[240_last]')]
    public ?float $last_240 = null;
}