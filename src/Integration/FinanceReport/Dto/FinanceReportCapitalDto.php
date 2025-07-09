<?php

namespace src\Integration\FinanceReport\Dto;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class FinanceReportCapitalDto
{
    #[SerializedPath('[error]')]
    public ?string $error = null;

    #[Assert\NotBlank]
    #[SerializedPath('[year]')]
    public ?string $year = null;
}