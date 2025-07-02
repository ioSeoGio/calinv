<?php

namespace src\Integration\Egr\Address;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class EgrAddressDto
{
    #[Assert\NotBlank]
    #[SerializedPath('[0][nsi00201][vnstranp]')]
    public ?string $country = null;

    #[Assert\NotBlank]
    #[SerializedPath('[0][nsi00239][vntnpk]')]
    public ?string $settlementType = null;

    #[Assert\NotBlank]
    #[SerializedPath('[0][vnp]')]
    public ?string $settlementName = null;

    #[Assert\NotBlank]
    #[SerializedPath('[0][nsi00226][vntulk]')]
    public ?string $placeType = null;

    #[Assert\NotBlank]
    #[SerializedPath('[0][vulitsa]')]
    public ?string $placeName = null;

    #[Assert\NotBlank]
    #[SerializedPath('[0][vdom]')]
    public ?string $houseNumber = null;

    #[Assert\NotBlank]
    #[SerializedPath('[0][nsi00227][vntpomk]')]
    public ?string $roomType = null;

    #[Assert\NotBlank]
    #[SerializedPath('[0][vpom]')]
    public ?string $roomNumber = null;

    #[SerializedPath('[0][vemail]')]
    public ?string $issuerEmail = null;

    #[SerializedPath('[0][vsite]')]
    public ?string $issuerSite = null;

    #[SerializedPath('[0][vtels]')]
    public ?string $issuerNumbers = null;
}