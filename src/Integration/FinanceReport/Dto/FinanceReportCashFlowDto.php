<?php

namespace src\Integration\FinanceReport\Dto;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class FinanceReportCashFlowDto
{
    public bool $isMock = false;

    #[SerializedPath('[error]')]
    public ?string $error = null;

    #[Assert\NotBlank]
    #[SerializedPath('[year]')]
    public ?string $year = null;

    #[SerializedPath('[traffic][020_actual]')]
    public ?float $_020 = null;

    #[SerializedPath('[traffic][021_actual]')]
    public ?float $_021 = null;

    #[SerializedPath('[traffic][022_actual]')]
    public ?float $_022 = null;

    #[SerializedPath('[traffic][023_actual]')]
    public ?float $_023 = null;

    #[SerializedPath('[traffic][024_actual]')]
    public ?float $_024 = null;

    #[SerializedPath('[traffic][030_actual]')]
    public ?float $_030 = null;

    #[SerializedPath('[traffic][031_actual]')]
    public ?float $_031 = null;

    #[SerializedPath('[traffic][032_actual]')]
    public ?float $_032 = null;

    #[SerializedPath('[traffic][033_actual]')]
    public ?float $_033 = null;

    #[SerializedPath('[traffic][034_actual]')]
    public ?float $_034 = null;

    #[SerializedPath('[traffic][040_actual]')]
    public ?float $_040 = null;

    #[SerializedPath('[traffic][050_actual]')]
    public ?float $_050 = null;

    #[SerializedPath('[traffic][051_actual]')]
    public ?float $_051 = null;

    #[SerializedPath('[traffic][052_actual]')]
    public ?float $_052 = null;

    #[SerializedPath('[traffic][053_actual]')]
    public ?float $_053 = null;

    #[SerializedPath('[traffic][054_actual]')]
    public ?float $_054 = null;

    #[SerializedPath('[traffic][055_actual]')]
    public ?float $_055 = null;

    #[SerializedPath('[traffic][060_actual]')]
    public ?float $_060 = null;

    #[SerializedPath('[traffic][061_actual]')]
    public ?float $_061 = null;

    #[SerializedPath('[traffic][062_actual]')]
    public ?float $_062 = null;

    #[SerializedPath('[traffic][063_actual]')]
    public ?float $_063 = null;

    #[SerializedPath('[traffic][064_actual]')]
    public ?float $_064 = null;

    #[SerializedPath('[traffic][070_actual]')]
    public ?float $_070 = null;

    #[SerializedPath('[traffic][080_actual]')]
    public ?float $_080 = null;

    #[SerializedPath('[traffic][081_actual]')]
    public ?float $_081 = null;

    #[SerializedPath('[traffic][082_actual]')]
    public ?float $_082 = null;

    #[SerializedPath('[traffic][083_actual]')]
    public ?float $_083 = null;

    #[SerializedPath('[traffic][084_actual]')]
    public ?float $_084 = null;

    #[SerializedPath('[traffic][090_actual]')]
    public ?float $_090 = null;

    #[SerializedPath('[traffic][091_actual]')]
    public ?float $_091 = null;

    #[SerializedPath('[traffic][092_actual]')]
    public ?float $_092 = null;

    #[SerializedPath('[traffic][093_actual]')]
    public ?float $_093 = null;

    #[SerializedPath('[traffic][094_actual]')]
    public ?float $_094 = null;

    #[SerializedPath('[traffic][095_actual]')]
    public ?float $_095 = null;

    #[SerializedPath('[traffic][100_actual]')]
    public ?float $_100 = null;

    #[SerializedPath('[traffic][110_actual]')]
    public ?float $_110 = null;

    #[SerializedPath('[traffic][120_actual]')]
    public ?float $_120 = null;

    #[SerializedPath('[traffic][130_actual]')]
    public ?float $_130 = null;

    #[SerializedPath('[traffic][140_actual]')]
    public ?float $_140 = null;
}