<?php

namespace src\Integration\Legat\Dto\egrEventDto;

use src\Integration\Egr\Event\EgrEventDto;
use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class LegatEgrEventsDto
{
    public ?string $error = null;

    #[SerializedPath('[change][requisites][name]')]
    /** @var string[]|null История изменения имен */
    public ?array $nameHistory = null;

    #[SerializedPath('[change][requisites][address]')]
    /** @var string[]|null История изменения имен */
    public ?array $addressHistory = null;

    #[Assert\Valid]
    #[SerializedPath('[change][egr]')]
    /** @var null|LegatEgrEventDto[] */
    public ?array $events = null;
}
