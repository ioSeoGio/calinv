<?php

namespace src\Integration\Legat\Dto\commonIssuerInfo;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class LegatDetailsDto
{
    /** 1 - Юрлицо, 2 - ИП, 0 - нет в ЕГР */
    #[Assert\NotBlank]
    #[SerializedPath('[type]')]
    public int $issuerType;

    #[Assert\NotBlank]
    #[SerializedPath('[unp]')]
    public string $pid;

    #[Assert\NotBlank]
    #[SerializedPath('[short]')]
    public string $shortIssuerName;

    #[Assert\NotBlank]
    #[SerializedPath('[full]')]
    public string $fullIssuerName;

    #[Assert\NotBlank]
    #[SerializedPath('[address]')]
    public string $fullAddress;

    /** Код налоговой инспекции, например 233 */
    #[Assert\NotBlank]
    #[SerializedPath('[insp_code]')]
    public string $inspectionCode;

    /** Инспекция МНС по Московскому району г. Бреста */
    #[Assert\NotBlank]
    #[SerializedPath('[insp_name]')]
    public string $inspectionName;

    #[Assert\Valid]
    #[Assert\NotBlank]
    #[SerializedPath('[fund]')]
    /** @var SocialFundInfoDto[] */
    public array $socialFundInfo = [];

    /** Дата постановки на налоговый учет, 1994-01-11 */
    #[Assert\NotBlank]
    #[SerializedPath('[add_date]')]
    public string $inspectionDate;

    /** Статус из реестра плательщиков МНС, Действующий */
    #[Assert\NotBlank]
    #[SerializedPath('[status_mns]')]
    public string $inspectionStatus;

    /** Дата изменения состояния, подозреваю что удаления из МНС, 0000-00-00 если не удален */
    #[SerializedPath('[del_date]')]
    public string $inspectionDeleteDate;

    /** Дата исключения из ЕГР, 0000-00-00 если не удален */
    #[SerializedPath('[del_date_egr]')]
    public string $egrDeleteDate;

    /** Пояснение статуса ликвидации, 0000-00-00 если не удален */
    #[SerializedPath('[likv]')]
    public string $liquidationDescription;

    /** Дата гос регистрации */
    #[Assert\NotBlank]
    #[SerializedPath('[date_reg]')]
    public string $governmentRegistrationDate;

    /** Наименование рег органа */
    #[Assert\NotBlank]
    #[SerializedPath('[reg_name]')]
    public string $governmentRegistrationDepartment;

    /** Запрет на отчуждение доли в уставном капитале */
    #[Assert\NotBlank]
    #[SerializedPath('[alienation]')]
    public bool $alienationForbidden;

    /** Статус в ЕГР, действующий */
    #[Assert\NotBlank]
    #[SerializedPath('[status_egr]')]
    public string $egrStatus;

    /** Почтовый код */
    #[Assert\NotBlank]
    #[SerializedPath('[post_code]')]
    public string $postCode;
}