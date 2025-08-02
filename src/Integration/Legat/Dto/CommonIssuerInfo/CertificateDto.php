<?php

namespace src\Integration\Legat\Dto\CommonIssuerInfo;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class CertificateDto
{
    #[SerializedPath('[type_id]')]
    public int $typeId;

    #[SerializedPath('[description]')]
    public string $description;

    #[SerializedPath('[count]')]
    public int $amount;
}