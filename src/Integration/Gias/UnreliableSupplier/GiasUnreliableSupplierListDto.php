<?php

namespace src\Integration\Gias\UnreliableSupplier;

use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Attribute\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class GiasUnreliableSupplierListDto
{
    #[SerializedPath('[number]')]
    public int $pageNumber;

    #[SerializedPath('[size]')]
    public int $pageSize;

    #[SerializedPath('[totalPages]')]
    public int $totalPages;

    #[SerializedPath('[totalElements]')]
    public int $totalElements;

    #[SerializedPath('[numberOfElements]')]
    public int $numberOfElements;

    #[SerializedPath('[first]')]
    public bool $first;

    #[SerializedPath('[last]')]
    public bool $last;

    /** @var GiasUnreliableSupplierDto[] */
    #[Assert\Valid]
    #[Assert\NotBlank]
    #[Context([
        'type' => GiasUnreliableSupplierDto::class
    ])]
    public array $content = [];
}