<?php

namespace src\Integration\Gias\UnreliableSupplier;

use Symfony\Component\Serializer\Attribute\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class GiasUnreliableSupplierDto
{
    #[Assert\NotBlank]
    public ?string $uuid = null;

    #[Assert\NotBlank]
    #[SerializedPath('[chainUuid]')]
    public ?string $chainUuid = null;

    #[Assert\NotBlank]
    #[SerializedPath('[author][uuid]')]
    public ?string $authorUuid = null;

    #[Assert\NotBlank]
    #[SerializedPath('[author][initials]')]
    public ?string $authorInitials = null;

    #[Assert\NotBlank]
    #[SerializedPath('[state]')]
    public ?string $state = null;

    #[Assert\NotBlank]
    #[SerializedPath('[name]')]
    public ?string $issuerName = null;

    #[Assert\NotBlank]
    #[SerializedPath('[providerunp]')]
    public ?string $pid = null;

    #[Assert\NotBlank]
    #[SerializedPath('[location]')]
    public ?string $issuerAddress = null;

    #[Assert\NotBlank]
    #[SerializedPath('[regnumber]')]
    public ?string $registrationNumber = null;

    #[Assert\NotBlank]
    #[SerializedPath('[adddate]')]
    public ?string $addDate = null;

    #[Assert\NotBlank]
    #[SerializedPath('[deldate]')]
    public ?string $deleteDate = null;

    #[Assert\NotBlank]
    #[SerializedPath('[baseincl]')]
    public ?string $reason = null;
}