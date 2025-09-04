<?php

namespace src\Entity\Issuer\AdditionalInfo;

use DateTimeImmutable;
use lib\Database\ApiFetchedActiveRecord;
use src\Entity\Issuer\Issuer;
use src\Integration\Legat\Dto\CommonIssuerInfo\CommonIssuerInfoDto;
use src\Integration\Legat\Dto\CommonIssuerInfo\RetailFacilityDto;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * @inheritDoc
 * @property int $issuerId
 * @property Issuer $issuer
 *
 * @property ?int $orderlyCourtAmountAsClaimant Кол-во судов приказного делопроизводства как взыскатель
 * @property ?int $orderlyCourtAmountAsDebtor Кол-во судов приказного делопроизводства как задолжник
 *
 * @property ?int $courtAmountAsPlaintiff Кол-во судов искового делопроизводства как истец
 * @property ?int $courtAmountAsDefendant Кол-во судов искового делопроизводства как ответчик
 * @property ?int $courtAmountOther Кол-во судов искового делопроизводства (другое)
 *
 * @property ?int $purchaseArchiveAsSupplierAmount Архив закупок, кол-во как поставщик
 * @property ?int $purchaseArchiveAsCustomerAmount Архив закупок, кол-во как заказчик
 * @property ?int $purchaseArchiveAsMemberAmount Архив закупок, кол-во как участник (не выбран исполнителем)
 *
 * @property ?int $registerOfContractsAsSupplierAmount Реестр договоров, как поставщик (данные icetrade.by)
 * @property ?int $registerOfContractsAsCustomerAmount Реестр договоров, как заказчик (данные icetrade.by)
 *
 * @property string[] $retailFacilities Торговые объекты
 * @property ?int $kgkPlannedInspectionAmount Запланированные проверки КГК
 * @property ?int $kgkEndedInspectionAmount Оконченные проверки КГК
 *
 * @property ?bool $isBankrupting В процедуре банкротства или банкрот
 *
 * @property string[] $debtTaxes Долги перед МНС
 * @property array{amount: float, date: string} $debtFszn Долги перед ФСЗН
 * @property array{date: string, reason: string, isExcludedFromRegister: int} $increasedEconomicOffense Сведения о фактах включения
 *      в реестр с повышенным риском совершения правонарушения в экономической сфере
 * @property ?string $directorName ФИО руководителя
 *
 * @property ?int $trademarksRegisteredAmount Кол-во товарных знаков, зарегистрированных компанией
 * @property int $industrialProductsAmount Кол-во продукции в реестре промышленных товаров
 * @property int $softRegisteredAmount Кол-во ПО в реестре программного обеспечения
 * @property int $foreignBranchesRfAmount Кол-во филиалов в РФ
 */
class IssuerAdditionalInfo extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return 'issuer_additional_info';
    }

    public function attributeLabels(): array
    {
        return [
            '_pid' => 'УНП',
            'retailFacilities' => 'Торговые объекты',
            'isBankrupting' => 'Банкрот или в процедуре банкротства',
            'debtTaxes' => 'Долги по уплате налогов в МНС',
            'debtFszn' => 'Долги по уплате взносов в ФСЗН',
            'increasedEconomicOffense' => 'В реестре с повышенным риском совершения правонарушения в экономической сфере',
            'directorName' => 'ФИО руководителя',
            'trademarksRegisteredAmount' => 'Кол-во товарных знаков, зарегистрированных компанией',
            'industrialProductsAmount' => 'Кол-во продукции в реестре промышленных товаров',
            'softRegisteredAmount' => 'Кол-во ПО в реестре программного обеспечения',
            'foreignBranchesRfAmount' => 'Кол-во филиалов в РФ',
        ];
    }

    public static function createOrUpdate(
        Issuer $issuer,
        CommonIssuerInfoDto $dto,
    ): self {
        $self = self::findOne(['issuerId' => $issuer->id]) ?: new self();
        $self->issuerId = $issuer->id;

        $self->orderlyCourtAmountAsClaimant = $dto->courtOrderlyAmountAsClaimant;
        $self->orderlyCourtAmountAsDebtor = $dto->courtOrderlyAmountAsDebtor;

        $self->courtAmountAsPlaintiff = $dto->courtAmountAsPlaintiff;
        $self->courtAmountAsDefendant = $dto->courtAmountAsDefendant;
        $self->courtAmountOther = $dto->courtAmountOther;

        $self->purchaseArchiveAsSupplierAmount = $dto->purchasedSupplierAmount;
        $self->purchaseArchiveAsCustomerAmount = $dto->purchasedCustomerAmount;
        $self->purchaseArchiveAsMemberAmount = $dto->purchasedMemberAmount;

        $self->registerOfContractsAsSupplierAmount = $dto->contractsProviderAmount;
        $self->registerOfContractsAsCustomerAmount = $dto->contractsClientAmount;

        $self->retailFacilities = array_map(fn (RetailFacilityDto $data) => $data->name . ' ' . $data->amount . ' шт.', $dto->retailFacilities);
        $self->kgkPlannedInspectionAmount = $dto->plannedInspectionsOfTheStateControlCommittee;
        $self->kgkEndedInspectionAmount = $dto->endedInspectionsOfTheStateControlCommittee;
        $self->isBankrupting = $dto->isBankruptInfoAvailable ? ((bool) $dto->isBankruptActiveOrEndedCase) : null;
        $self->debtTaxes = $dto->debtTaxes;
        $self->debtFszn = $dto->debtFszn;
        $self->increasedEconomicOffense = $dto->increasedEconomicOffenseDtos;
        $self->directorName = $dto->directorInfo?->director;

        $self->trademarksRegisteredAmount = $dto->trademarksAmount;
        $self->industrialProductsAmount = $dto->industrialProductsAmount;
        $self->softRegisteredAmount = $dto->softRegistryAmount;
        $self->foreignBranchesRfAmount = $dto->foreignBranchesRfAmount;

        $self->renewLastApiUpdateDate();

        return $self;
    }

    public function hasTaxesDebt(): bool
    {
        return !empty($this->debtFszn) || !empty($this->debtTaxes);
    }

    public function getLatestDebtDate(): ?DateTimeImmutable
    {
        if (!$this->hasTaxesDebt()) {
            return null;
        }

        $taxesDebt = array_merge(
            $this->debtTaxes,
            ArrayHelper::getColumn($this->debtFszn, 'date')
        );

        usort($taxesDebt, function ($a, $b) {
            return strtotime($b) - strtotime($a);
        });

        return DateTimeImmutable::createFromFormat('Y-m-d', reset($taxesDebt)) ?: null;
    }

    public function getIssuer(): ActiveQuery
    {
        return $this->hasOne(Issuer::class, ['id' => 'issuerId']);
    }
}
