<?php

namespace src\Integration\Legat\Dto\commonIssuerInfo;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class LiquidationDto
{
    #[Assert\NotBlank]
    #[SerializedPath('[number]')]
    public string $liquidationDecisionNumber;

    #[Assert\NotBlank]
    #[SerializedPath('[date_begin]')]
    public string $beginDate;

    #[Assert\NotBlank]
    #[SerializedPath('[name_liquidator]')]
    public string $liquidatorName;

    #[Assert\NotBlank]
    #[SerializedPath('[address]')]
    public string $liquidatorAddress;

    #[Assert\NotBlank]
    #[SerializedPath('[phone]')]
    public string $liquidatorPhones;

    #[SerializedPath('[work]')]
    public string $liquidatorWorkTime;

    /** Срок принятия претензий в месяцах с момента опубликования */
    #[SerializedPath('[term]')]
    public int $periodForAcceptingClaimsInMonths;

    /** Срок принятия претензий в месяцах с момента опубликования */
    #[SerializedPath('[date_pub]')]
    public string $publicationDate;

    #[SerializedPath('[status]')]
    public string $status;

    /** Дата присвоения текущего статуса, 0000-00-00 */
    #[SerializedPath('[date_current]')]
    public ?string $currentStatusClaimDate;
}