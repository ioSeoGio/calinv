<?php

namespace src\Integration\Bcse\AllShareList;

use Symfony\Component\Serializer\Attribute\SerializedPath;
use Symfony\Component\Validator\Constraints\NotBlank;

class BcseShareBaseInfoFromList
{
    #[NotBlank]
    #[SerializedPath('[issuer]')]
    public string $issuerName;

    #[NotBlank]
    #[SerializedPath('[tin]')]
    public string $pid;

    #[NotBlank]
    public string $ticker;

    #[NotBlank]
    #[SerializedPath('[number]')]
    public string $registerNumber;

    #[NotBlank]
    public string $type;
}
