<?php

namespace src\Entity\Issuer;

use DateTimeImmutable;
use lib\Database\ApiFetchedActiveRecord;
use src\Entity\Issuer\AdditionalInfo\IssuerAdditionalInfo;
use src\Entity\Issuer\AdditionalInfo\IssuerLiquidationInfo;
use src\Entity\Issuer\AddressInfo\AddressInfo;
use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfo;
use src\Entity\Issuer\CreditRating\CreditRating;
use src\Entity\Issuer\CreditRating\CreditRatingInfo;
use src\Entity\Issuer\EmployeeAmount\EmployeeAmountRecord;
use src\Entity\Issuer\EsgRating\EsgRatingInfo;
use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;
use src\Entity\Issuer\FinanceReport\AvailableFinancialReportData;
use src\Entity\Issuer\FinanceReport\CashFlowReport\CashFlowReport;
use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReport;
use src\Entity\Issuer\TypeOfActivity\TypeOfActivity;
use src\Entity\Issuer\UnreliableSupplier\UnreliableSupplier;
use src\Entity\Share\Share;
use src\Integration\Egr\TypeOfActivity\EgrTypeOfActivityDto;
use src\Integration\Legat\Dto\CommonIssuerInfo\LiquidationDto;
use yii\db\ActiveQuery;

/**
 * @inheritDoc
 * @property int $id
 * @property ?string $name
 * @property ?string $description Описание, введенное вручную модератором
 * @property ?string $typeOfActivity Вид экономической деятельности
 * @property ?string $typeOfActivityCode ОКЭД - код экономической деятельности
 *
 * @property ?BusinessReputationInfo $businessReputationInfo Рейтинг деловой репутации BIK
 * @property ?AddressInfo $addressInfo
 * @property ?EsgRatingInfo $esgRatingInfo Рейтинг ESG
 * @property ?CreditRatingInfo $creditRatingInfo Кредитный рейтинг
 * @property ?UnreliableSupplier $unreliableSupplier Запись о ненадежном поставщике
 * @property Share[] $shares Акции эмитента
 * @property Share[] $activeShares Акции в обороте
 * @property ProfitLossReport[] $profitLossReports Отчеты о прибылях и убытках
 * @property AccountingBalance[] $accountBalanceReports Бухгалтерские отчеты
 * @property CashFlowReport[] $cashFlowReports Отчеты о движении денежных средств
 * @property EmployeeAmountRecord[] $employeeAmountRecords Данные о кол-ве сотрудников в компании
 * @property ?IssuerAdditionalInfo $additionalInfo Дополнительные данные
 * @property ?IssuerLiquidationInfo $liquidationInfo Сведения о ликвидации
 *
 * @property string[] $fullnessState
 *
 * @property IssuerLegalStatus $legalStatus
 * @property ?string $_legalStatus
 *
 * @property ?string $_dateFinanceReportsInfoUpdated Дата, когда была запрошена информация об имеющихся фин. отчетностях
 * @property ?\DateTimeImmutable $dateFinanceReportsInfoUpdated Дата, когда была запрошена информация об имеющихся фин. отчетностях
 *
 * @property ?\DateTimeImmutable $dateShareInfoModerated Дата, когда информация по акциям вручную проверена и подтверждена
 * @property ?string $_dateShareInfoModerated
 *
 * @property bool $isVisible Показывать эмитента пользователям (не админам)
 *
 * @property PayerIdentificationNumber $pid
 * @property string $_pid
 */
class Issuer extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return 'issuer';
    }

    public function attributeLabels(): array
    {
        return [
            '_legalStatus' => 'Статус',
            'name' => 'Наименование',
            'typeOfActivity' => 'Вид деятельности',
            'typeOfActivityCode' => 'ОКЭД',
            'fullnessState' => 'Заполненность',
            'expressRating' => 'Экспресс рейтинг',
            '_pid' => 'УНП',
        ];
    }

    public static function createOrGet(PayerIdentificationNumber $pid): Issuer
    {
        return self::findOne([
            '_pid' => $pid->id
        ]) ?: new self([
            '_pid' => $pid->id,
            'fullnessState' => [IssuerFullnessState::initial],
            'isVisible' => true,
        ]);
    }

    public function updateName(
        string $name,
    ): self {
        $this->name = $name;
        $this->renewLastApiUpdateDate();

        return $this;
    }

    public function updateLegalStatus(IssuerLegalStatus $legalStatus): void
    {
        $this->_legalStatus = $legalStatus->value;
    }

    public function updateTypeOfActivity(EgrTypeOfActivityDto $dto): void
    {
        $this->typeOfActivity = $dto->typeOfActivityName;
        $this->typeOfActivityCode = $dto->typeOfActivityCode;
    }

    public function getDateFinanceReportsInfoUpdated(): ?\DateTimeInterface
    {
        return $this->_dateFinanceReportsInfoUpdated ? new \DateTimeImmutable($this->_dateShareInfoModerated) : null;
    }

    public function renewDateFinanceReportsInfoUpdated(): void
    {
        $this->_dateFinanceReportsInfoUpdated = (new DateTimeImmutable())->format(DATE_ATOM);
    }

    public function getDateShareInfoModerated(): ?\DateTimeInterface
    {
        return $this->_dateShareInfoModerated ? new \DateTimeImmutable($this->_dateShareInfoModerated) : null;
    }

    public function markShareInfoNotModerated(): void
    {
        $this->_dateShareInfoModerated = null;
    }

    public function setDateShareInfoModerated(DateTimeImmutable $date): void
    {
        $this->_dateShareInfoModerated = $date->format(DATE_ATOM);
    }

    public static function findByIssuerName(string $issuerName): ?self
    {
        return self::findOne(['name' => $issuerName]);
    }

    public function hasState(IssuerFullnessState $state): bool
    {
        return !empty(array_filter($this->fullnessState, function (array|IssuerFullnessState $value) use ($state) {
            return is_array($value) ? $state->equalsString($value['value']) : $value->equals($value);
        }));
    }

    public function addFullnessState(IssuerFullnessState $state): void
    {
        if (!$this->hasState($state)) {
            $this->fullnessState = array_merge($this->fullnessState, [$state]);
        }
    }

    public function removeFullnessState(IssuerFullnessState $state): void
    {
        $this->fullnessState = array_filter($this->fullnessState, function (array|IssuerFullnessState $value) use ($state) {
            $isEquals = is_array($value) ? $state->equalsString($value['value']) : $value->equals($value);
            return $isEquals === false;
        });
    }

    public static function findByPid(PayerIdentificationNumber $pid): ?Issuer
    {
        return self::findOne(['_pid' => $pid->id]);
    }

    public function setFullnessState(IssuerFullnessState ...$states): void
    {
        $this->fullnessState = $states;
    }

    public function getLegalStatus(): IssuerLegalStatus
    {
        return IssuerLegalStatus::from($this->_legalStatus);
    }

    public function getPid(): PayerIdentificationNumber
    {
        return new PayerIdentificationNumber($this->_pid);
    }

    public function getShares(): ActiveQuery
    {
        return $this->hasMany(Share::class, ['issuer_id' => 'id']);
    }

    public function getActiveShares(): ActiveQuery
    {
        return $this->hasMany(Share::class, ['issuer_id' => 'id'])
            ->andWhere(Share::tableName() . '."closingDate" IS NULL');
    }

    public function getProfitLossReports(): ActiveQuery
    {
        return $this->hasMany(ProfitLossReport::class, ['issuer_id' => 'id'])
            ->addOrderBy(['_year' => SORT_DESC]);
    }

    public function getAccountBalanceReports(): ActiveQuery
    {
        return $this->hasMany(AccountingBalance::class, ['issuer_id' => 'id'])
            ->addOrderBy(['_year' => SORT_DESC]);
    }

    public function getCashFlowReports(): ActiveQuery
    {
        return $this->hasMany(CashFlowReport::class, ['issuer_id' => 'id'])
            ->addOrderBy(['_year' => SORT_DESC]);
    }

    public function getTypeOfActivity(): ActiveQuery
    {
        return $this->hasOne(TypeOfActivity::class, ['_pid' => '_pid']);
    }

    public function getAddressInfo(): ActiveQuery
    {
        return $this->hasOne(AddressInfo::class, ['_pid' => '_pid']);
    }

    public function getEsgRatingInfo(): ActiveQuery
    {
        return $this->hasOne(EsgRatingInfo::class, ['issuerId' => 'id']);
    }

    public function getCreditRatingInfo(): ActiveQuery
    {
        return $this->hasOne(CreditRatingInfo::class, ['issuerId' => 'id']);
    }

    public function getUnreliableSupplier(): ActiveQuery
    {
        return $this->hasOne(UnreliableSupplier::class, ['_pid' => '_pid']);
    }

    public function getBusinessReputationInfo(): ActiveQuery
    {
        return $this->hasOne(BusinessReputationInfo::class, ['issuerId' => 'id']);
    }

    public function getAdditionalInfo(): ActiveQuery
    {
        return $this->hasOne(IssuerAdditionalInfo::class, ['issuerId' => 'id']);
    }

    public function getLiquidationInfo(): ActiveQuery
    {
        return $this->hasOne(IssuerLiquidationInfo::class, ['issuerId' => 'id']);
    }

    public function getEmployeeAmountRecords(): ActiveQuery
    {
        return $this->hasMany(EmployeeAmountRecord::class, ['issuerId' => 'id'])
            ->addOrderBy(['_date' => SORT_ASC]);
    }

    public function getLatestAvailableFinancialReportData(): ActiveQuery
    {
        return $this->hasMany(AvailableFinancialReportData::class, ['issuerId' => 'id'])
            ->addOrderBy(['_year' => SORT_DESC]);
    }

    public static function findVisible(): ActiveQuery
    {
        return self::find()->andWhere(['isVisible' => true]);
    }
}
