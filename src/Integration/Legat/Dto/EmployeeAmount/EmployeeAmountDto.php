<?php

namespace src\Integration\Legat\Dto\EmployeeAmount;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class EmployeeAmountDto
{
    public bool $isMock = false;

    #[SerializedPath('[error]')]
    public ?string $error = null;

    public int $total;

    #[Assert\Valid]
    #[SerializedPath('[employees]')]
    /** @var AmountEmployeeDto[] */
    public array $data = [];
}