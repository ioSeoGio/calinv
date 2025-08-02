<?php

namespace src\Integration\Legat\Dto\CommonIssuerInfo;

use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

class CommonIssuerInfoDto
{
    public bool $isMock = false;

    #[SerializedPath('[error]')]
    public ?string $error = null;

    #[Assert\Valid]
    #[Assert\NotBlank]
    #[SerializedPath('[details]')]
    public LegatDetailsDto $detailsDto;

    /** Данные приказного производства, кол-во в качестве взыскателя */
    #[SerializedPath('[courts][claimant]')]
    public ?int $courtOrderlyAmountAsClaimant = null;

    /** Данные приказного производства, кол-во в качестве должника */
    #[SerializedPath('[courts][debtor]')]
    public ?int $courtOrderlyAmountAsDebtor = null;

    /** Данные искового производства, кол-во в качестве истца */
    #[SerializedPath('[courts][ist]')]
    public ?int $courtAmountAsPlaintiff = null;

    /** Данные искового производства, кол-во в качестве ответчика */
    #[SerializedPath('[actional][otv]')]
    public ?int $courtAmountAsDefendant = null;

    /** Данные искового производства, другие */
    #[SerializedPath('[actional][dr]')]
    public ?int $courtAmountOther = null;

    /** Архив закупок, поставщик */
    #[SerializedPath('[agreements][supplier]')]
    public ?int $purchasedSupplierAmount = null;

    /** Архив закупок, заказчик */
    #[SerializedPath('[agreements][customer]')]
    public ?int $purchasedCustomerAmount = null;

    /** Архив закупок, участник (не выбран поставщиком) */
    #[SerializedPath('[agreements][other]')]
    public ?int $purchasedMemberAmount = null;

    /** Реестр договоров (данные icetrade.by), поставщик */
    #[SerializedPath('[contracts][provider]')]
    public ?int $contractsProviderAmount = null;

    /** Реестр договоров (данные icetrade.by), заказчик */
    #[SerializedPath('[contracts][client]')]
    public ?int $contractsClientAmount = null;

    /** Сведения о гос и коммерческих закупках с участием компании в роли Заказчика, Объявленные */
    #[SerializedPath('[zakupki][declared]')]
    public ?int $purchasedDeclaredAmount = null;

    /** Сведения о гос и коммерческих закупках с участием компании в роли Заказчика, Подведение итогов */
    #[SerializedPath('[zakupki][results]')]
    public ?int $purchasedResultsAmount = null;

    /** Сведения о гос и коммерческих закупках с участием компании в роли Заказчика, Завершенные */
    #[SerializedPath('[zakupki][end]')]
    public ?int $purchasedEndAmount = null;

    #[SerializedPath('[sales]')]
    /** @var RetailFacilityDto[] */
    public array $retailFacilities = [];

    #[SerializedPath('[consumer][obo]')]
    /** Кол-во объектов бытового обслуживания */
    public ?int $householdConsumerServicesFacilitiesAmount = null;

    #[SerializedPath('[consumer][sbo]')]
    /** Кол-во субъектов бытового обслуживания */
    public ?int $householdConsumerServicesSubjectsFacilitiesAmount = null;

    #[SerializedPath('[kgk][plan]')]
    public ?int $plannedInspectionsOfTheStateControlCommittee = null;

    #[SerializedPath('[kgk][end]')]
    public ?int $endedInspectionsOfTheStateControlCommittee = null;

    /** Сведения о банкротстве, показатель наличия сведений/процедуры */
    #[SerializedPath('[bankrupt][status]')]
    public int $isBankruptInfoAvailable;

    /** Сведения о банкротстве, индикатор активного или завершенного дела */
    #[SerializedPath('[bankrupt][file_status]')]
    public ?int $isBankruptActiveOrEndedCase;

    /** Сведения о ликвидации */
    #[SerializedPath('[liquidation]')]
    #[Assert\Valid]
    public ?LiquidationDto $liquidation = null;

    /**
     * @var null|string[] сведения о фактах задолженности перед бюджетом по налогам,
     * сборам (пошлинам), пеням на 1-е число месяца, следующего за отчетным (данные МНС)
     */
    #[SerializedPath('[debt]')]
    public ?array $debtTaxes = [];

    #[SerializedPath('[fszn]')]
    /**
     * @var null|FsznDebtDto[] сведения о фактах задолженности по страховым взносам
     * в ФСЗН Мин труда и соц защиты РБ свыше 10 000,00 рублей до 01.01.2019,
     * свыше 100,00 рублей после 01.01.2019 по состоянию на 1 число месяца,
     * следующего за отчетным кварталом
     */
    public ?array $debtFszn = [];

    /**
     * @var null|IncreasedEconomicOffenseDto[] сведения о фактах включения в реестр субъектов хозяйствования
     * с повышенным риском совершения правонарушения в экономической сфере
     */
    #[SerializedPath('[ngb]')]
    public ?array $increasedEconomicOffenseDtos = null;

    #[SerializedPath('[nbrb][principal]')]
    public ?int $bankGuaranteesPrincipalsAmount = null;

    #[SerializedPath('[nbrb][beneficiary]')]
    public ?int $bankGuaranteesBeneficiaryAmount = null;

    #[SerializedPath('[tenant]')]
    /**
     * @var null|string[] сведения о фактах включения в реестр задолженности за аренду
     * государственного имущества по объектам недвижимости, расположенным в г. Минске, Могилевской и Брестской области
     */
    public ?array $tenantDebts = null;

    #[SerializedPath('[lic][license]')]
    public ?int $licensesAmount = null;

    #[SerializedPath('[lic][permission]')]
    public ?int $permissionsAmount = null;

    /** аттестаты соответствия (строительные работы) */
    #[SerializedPath('[att]')]
    public ?int $attestationsAmount = null;

    #[SerializedPath('[beltpp]')]
    public ?DirectorInfoDto $directorInfo = null;

    #[SerializedPath('[cert]')]
    /** @var CertificateDto[] сертификаты и декларации соответствия */
    public ?array $certificates = null;

    /** товарные знаки, зарегистрированные компанией */
    #[SerializedPath('[signs]')]
    public ?int $trademarksAmount = null;

    /** количество филиалов и представительств на территории РФ */
    #[SerializedPath('[foreign_branch_rf]')]
    public ?int $foreignBranchesRfAmount = null;

    /** количество продукции в реестре промышленных товаров */
    #[SerializedPath('[industrial_products]')]
    public ?int $industrialProductsAmount = null;

    /** количество ПО в реестре программного обеспечения */
    #[SerializedPath('[soft_registry]')]
    public ?int $softRegistryAmount = null;
}