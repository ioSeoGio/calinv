<?php

namespace src\Integration\Egr\TypeOfActivity;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class EgrTypeOfActivityDto
{
    #[Assert\NotBlank]
    #[SerializedPath('[0][ngrn]')]
    public ?string $pid = null;

    #[Assert\NotBlank]
    #[SerializedPath('[0][dfrom]')]
    public ?\DateTimeImmutable $dateFrom = null;

    #[SerializedPath('[0][dto]')]
    public ?\DateTimeImmutable $dateTo = null;

    public bool $isActive;

    #[Assert\NotBlank]
    #[SerializedPath('[0][nsi00114][vkvdn]')]
    public ?string $typeOfActivityCode = null;

    #[Assert\NotBlank]
    #[SerializedPath('[0][nsi00114][vnvdnp]')]
    public ?string $typeOfActivityName = null;

    #[SerializedPath('[0][cact]')]
    public ?string $cact = null;
}