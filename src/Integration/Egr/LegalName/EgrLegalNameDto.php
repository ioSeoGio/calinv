<?php

namespace src\Integration\Egr\LegalName;

use src\Integration\Egr\EgrLegalStatus;
use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class EgrLegalNameDto
{
    #[Assert\NotBlank]
    #[SerializedPath('[0][vn]')]
    public ?string $legalName = null;

    #[Assert\NotBlank]
    #[SerializedPath('[0][nsi00219][vnsostk]')]
    public ?EgrLegalStatus $status = null;

    #[SerializedPath('[0][dfrom]')]
    public ?string $fromDate = null;

    #[SerializedPath('[0][vnaim]')]
    public ?string $longLegalName = null;

    #[SerializedPath('[0][vfn]')]
    public ?string $shortLegalName = null;
}