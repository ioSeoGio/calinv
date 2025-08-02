<?php

namespace src\Integration\Legat\Dto\commonIssuerInfo;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class FsznDebtDto
{
    #[SerializedPath('[debt]')]
    public float $amount;

    #[SerializedPath('[date]')]
    public string $date;
}