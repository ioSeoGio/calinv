<?php

namespace src\Entity\Share;

use DateTimeImmutable;
use lib\Database\ApiFetchedActiveRecord;
use src\Entity\Issuer\Issuer;
use src\Entity\Share\Deal\ShareDealRecord;
use src\Integration\Bcse\ShareInfo\BcseShareLastDealDto;
use yii\db\ActiveQuery;

/**
 * @inheritDoc
 * @property int $id
 *
 * @property string $lastDealChangePercent Изменение цены из-за последней сделки в %
 * @property string $lastDealDate Дата последней сделки
 * @property ?float $currentPrice Цена по последней сделке на бирже (БВФБ)
 *
 * @property ShareFullnessState $fullnessStateEnum Заполненность акции данными
 * @property string $fullnessState Заполненность акции данными
 * @property int $issuer_id
 * @property Issuer $issuer
 *
 * @property string $nationalId Национальный id выпуска
 * @property int $orderedIssueId Порядковый номер выпуска
 * @property string $registerNumber Номер регистрации
 * @property ShareRegisterNumber $registerNumberObject Номер регистрации (объект)
 * @property float $denomination Номинал
 *
 * @property int $simpleIssuedAmount Кол-во обычных акций в выпуске
 * @property int $privilegedIssuedAmount Кол-во привилегированных акций в выпуске
 * @property int $totalIssuedAmount Общее кол-во в выпуске
 *
 * @property string $issueDate Дата выпуска
 * @property ?string $closingDate Дата снятия с учета и хранения
 */
class Share extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return 'share';
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Имя выпуска',
            'issuer_id' => 'Эмитент',
            'denomination' => 'Номинал',
            'currentPrice' => 'Текущая цена',
            'volumeIssued' => 'Объем выпуска',
            'registerNumber' => 'Регистрационный номер',
            'lastDealDate' => 'Дата последней сделки',
            'lastDealChangePercent' => 'Изменение по последней сделке',
            'totalIssuedAmount' => 'Объем выпуска',
            'issueDate' => 'Дата выпуска',
            'closingDate' => 'Дата изъятия',
        ];
    }

    public static function createOrUpdate(
        int $issuerId,
        string $nationalId,
        int $orderedIssueId,
        string $registerNumber,
        float $denominationPrice,
        int $simpleIssuedAmount,
        int $privilegedIssuedAmount,
        int $totalIssuedAmount,
        DateTimeImmutable $issueDate,
        ?DateTimeImmutable $closingDate,
    ): self {
        $self = self::findOne([
            'issuer_id' => $issuerId,
            'registerNumber' => $registerNumber,
        ]) ?: new self([
            'issuer_id' => $issuerId,
            'registerNumber' => $registerNumber,
            'fullnessState' => [ShareFullnessState::initial],
        ]);

        $self->nationalId = $nationalId;
        $self->orderedIssueId = $orderedIssueId;
        $self->denomination = $denominationPrice;
        $self->simpleIssuedAmount = $simpleIssuedAmount;
        $self->privilegedIssuedAmount = $privilegedIssuedAmount;
        $self->totalIssuedAmount = $totalIssuedAmount;
        $self->issueDate = $issueDate->format(DATE_ATOM);
        $self->closingDate = $closingDate?->format(DATE_ATOM);

        $self->renewLastApiUpdateDate();

        return $self;
    }

    public function isActive(): bool
    {
        return $this->closingDate === null;
    }

    public function setLastDealInfo(?BcseShareLastDealDto $lastDealDto): void
    {
        if ($lastDealDto === null) {
            $this->setFullnessState(ShareFullnessState::initial);
            return;
        }

        $this->currentPrice = $lastDealDto->price;
        $this->lastDealDate = $lastDealDto->date->format(DATE_ATOM);
        $this->lastDealChangePercent = $lastDealDto->changeFromPreviousDealPercent;

        $this->renewLastApiUpdateDate();
    }

    public function getFormattedName(): string
    {
        return ($this->isPrivileged() ? 'АП'  : 'А') . $this->orderedIssueId;
    }

    public function getFormattedNameWithIssuer(): string
    {
        return $this->issuer->name . ' - ' . $this->getFormattedName();
    }

    public function isPrivileged(): bool
    {
        return $this->privilegedIssuedAmount > 0;
    }

    public function setFullnessState(ShareFullnessState $shareFullnessState): void
    {
        $this->fullnessState = $shareFullnessState->value;
    }

    public function getFullnessStateEnum(): ShareFullnessState
    {
        return ShareFullnessState::from($this->fullnessState);
    }

    public function getRegisterNumberObject(): ShareRegisterNumber
    {
        return new ShareRegisterNumber($this->registerNumber);
    }

    public function getIssuer(): ActiveQuery
    {
        return $this->hasOne(Issuer::class, ['id' => 'issuer_id']);
    }

    public static function findActive(): ActiveQuery
    {
        return self::find()->andWhere('"closingDate" IS NULL');
    }

    public function getShareDeals(): ActiveQuery
    {
        return $this->hasMany(ShareDealRecord::class, ['share_id' => 'id']);
    }
}
