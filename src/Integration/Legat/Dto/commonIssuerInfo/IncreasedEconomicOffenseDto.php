<?php

namespace src\Integration\Legat\Dto\commonIssuerInfo;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class IncreasedEconomicOffenseDto
{
    #[SerializedPath('[date]')]
    public string $date;

    #[SerializedPath('[base]')]
    public string $reason;

    #[SerializedPath('[end]')]
    public int $isExcludedFromRegister;
}