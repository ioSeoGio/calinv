<?php

namespace src\Entity\Share\Deal;

use DateTimeImmutable;
use lib\Database\ApiFetchedActiveRecord;
use src\Entity\Issuer\Issuer;
use src\Entity\Share\Share;

/**
 * Запись о сделках в определенный день
 *
 * @inheritDoc
 * @property int $id
 *
 * @property string $_date Дата дня записи о сделках
 * @property DateTimeImmutable $date Дата дня записи о сделках
 *
 * @property string $currency Валюта сделок
 *
 * @property float $minPrice Минимальная цена
 * @property float $maxPrice Максимальная цена
 * @property float $weightedAveragePrice Средневзвешенная цена
 *
 * @property float $totalSum Сумма всех сделок
 * @property int $totalAmount Кол-во купленных акций
 * @property int $totalDealAmount Кол-во сделок
 *
 * @property Share $share
 * @property Issuer $issuer
 */
class ShareDealRecord extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return 'share_deal';
    }

    public function attributeLabels(): array
    {
        return [
            '_date' => 'День сделки',
            'currency' => 'Валюта',
            'minPrice' => 'Минимальная цена',
            'maxPrice' => 'Максимальная цена',
            'weightedAveragePrice' => 'Средневзвешенная цена',
            'totalSum' => 'Сумма всех сделок',
            'totalAmount' => 'Кол-во купленных акций',
            'totalDealAmount' => 'Кол-во сделок',
        ];
    }

    public static function createOrUpdate(
        Share $share,

        DateTimeImmutable $date,
        string $currency,
        float $minPrice,
        float $maxPrice,
        float $weightedAveragePrice,

        float $totalSum,
        int $totalAmount,
        int $totalDealAmount,
    ): self {
        $self = self::findOne([
            'share_id' => $share->id,
            '_date' => $date->format(DATE_ATOM),
        ]) ?: new self([
            'share_id' => $share->id,
            '_date' => $date->format(DATE_ATOM),
        ]);

        $self->currency = $currency;
        $self->minPrice = $minPrice;
        $self->maxPrice = $maxPrice;
        $self->weightedAveragePrice = $weightedAveragePrice;
        $self->totalSum = $totalSum;
        $self->totalAmount = $totalAmount;
        $self->totalDealAmount = $totalDealAmount;

        $self->renewLastApiUpdateDate();

        return $self;
    }

    public function getDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(DATE_ATOM, $this->_date);
    }

    public function getShare(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Share::class, ['id' => 'share_id']);
    }

    public function getIssuer(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Issuer::class, ['id' => 'issuer_id'])
            ->viaTable('share_deal_issuer', ['share_id' => 'id']);
    }
}
