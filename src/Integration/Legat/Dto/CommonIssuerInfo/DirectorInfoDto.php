<?php

namespace src\Integration\Legat\Dto\CommonIssuerInfo;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class DirectorInfoDto
{
    #[Assert\NotBlank]
    #[SerializedPath('[director]')]
    public string $director;

    #[SerializedPath('[tel]')]
    public string $phone;

    #[SerializedPath('[fax]')]
    public ?string $fax = null;

    #[SerializedPath('[email]')]
    public string $email;

    #[SerializedPath('[site]')]
    public string $site;
}