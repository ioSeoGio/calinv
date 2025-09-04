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
 * @property ?float $minPrice Минимальная цена за промежуток на бирже (БВФБ)
 * @property ?float $maxPrice Максимальная цена за промежуток на бирже (БВФБ)
 * @property ?float $currentPrice Цена по последней сделке на бирже (БВФБ)
 *
 * @property ShareFullnessState $fullnessStateEnum Заполненность акции данными
 * @property string $fullnessState Заполненность акции данными
 * @property int $issuer_id
 * @property Issuer $issuer
 *
 * @property string $name Имя вида "ОАО Белмедстекло АП18" - название эмитента + номер выпуска
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
 *
 * @property ?ShareDealRecord $lastShareDeal
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
            'lastDealDate' => 'Последняя сделка',
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

        $self->name = $self->getFormattedNameWithIssuer();
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

    public function countBoundaryPrice(): void
    {
        $sharePriceBounds = ShareDealRecord::find()
            ->select([
                'MIN("minPrice") as minPrice',
                'MAX("maxPrice") as maxPrice',
                'COUNT(id) as count',
            ])
            ->andWhere(['share_id' => $this->id])
            ->asArray()
            ->one();

        if (!empty($sharePriceBounds['count'])) {
            $this->minPrice = $sharePriceBounds['minPrice'];
            $this->maxPrice = $sharePriceBounds['maxPrice'];
        }
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

    public function getLastShareDeal(): ActiveQuery
    {
        return $this->hasOne(ShareDealRecord::class, ['share_id' => 'id'])->orderBy(['_date' => SORT_DESC]);
    }

    public static function getShareIdsWithDeals(): array
    {
        return ShareDealRecord::find()
            ->select(['share_id'])
            ->distinct()
            ->asArray()
            ->column();
    }
}
