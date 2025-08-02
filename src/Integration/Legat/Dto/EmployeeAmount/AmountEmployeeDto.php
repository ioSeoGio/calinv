<?php

namespace src\Integration\Legat\Dto\EmployeeAmount;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class AmountEmployeeDto
{
    #[SerializedPath('[strength]')]
    public int $strength;

    #[Assert\NotBlank]
    #[SerializedPath('[year]')]
    public int $year;

    #[Assert\NotBlank]
    #[SerializedPath('[date]')]
    public string $date;
}
