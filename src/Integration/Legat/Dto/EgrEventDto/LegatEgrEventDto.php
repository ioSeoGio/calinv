<?php

namespace src\Integration\Legat\Dto\EgrEventDto;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class LegatEgrEventDto
{
    #[Assert\NotBlank]
    #[SerializedPath('[name]')]
    public ?string $eventName = null;

    #[Assert\NotBlank]
    #[SerializedPath('[date]')]
    public ?string $eventDate = null;
}