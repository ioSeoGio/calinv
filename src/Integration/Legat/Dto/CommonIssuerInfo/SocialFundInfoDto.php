<?php

namespace src\Integration\Legat\Dto\CommonIssuerInfo;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class SocialFundInfoDto
{
    /** Номер плательщика ФСЗН 151000330 */
    #[Assert\NotBlank]
    #[SerializedPath('[unpf]')]
    public string $payerNumber;

    /**
     * Категория плательщика
     * 1 - юридическое лицо,
     * 2 - индивидуальный предприниматель за себя,
     * 3 - индивидуальный предприниматель за наемных работников
     */
    #[Assert\NotBlank]
    #[SerializedPath('[type]')]
    public string $payerType;

    /** Код подразделения ФСЗН */
    #[Assert\NotBlank]
    #[SerializedPath('[fund_code]')]
    public string $fundCode;

    /** Ленинский районный отдел ФСЗН г. Бреста */
    #[Assert\NotBlank]
    #[SerializedPath('[fund_name]')]
    public string $fundName;

    /** Дата постановки на учет в ФСЗН, 1992-01-10 */
    #[Assert\NotBlank]
    #[SerializedPath('[fund_date]')]
    public string $fundDate;
}