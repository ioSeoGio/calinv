<?php

namespace src\Integration\Legat\Dto\commonIssuerInfo;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class RetailFacilityDto
{
    #[Assert\NotBlank]
    #[SerializedPath('[objects]')]
    public string $name;

    #[Assert\NotBlank]
    #[SerializedPath('[object_id]')]
    public int $objectId;

    #[Assert\NotBlank]
    #[SerializedPath('[count]')]
    public int $amount;
}