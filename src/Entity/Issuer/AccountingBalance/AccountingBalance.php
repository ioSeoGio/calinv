<?php

namespace src\Entity\Issuer\AccountingBalance;

use DateTimeImmutable;
use lib\Database\ApiFetchedActiveRecord;
use src\Entity\Issuer\FinanceTermType;
use src\Entity\Issuer\Issuer;
use yii\db\ActiveQuery;

/**
 * @inheritDoc
 *
 * @property FinanceTermType $termType
 * @property string $_termType
 *
 * @property string $year
 * @property DateTimeImmutable $yearDto
 *
 * @property float $longAsset
 * @property float $shortAsset
 * @property float $shortDebt
 * @property float $longDebt
 * @property float $capital
 *
 * @property Issuer $issuer
 */
class AccountingBalance extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return 'issuer_accounting_balance';
    }

    public function attributeLabels(): array
    {
        return [
            'year' => 'Год',
            '_termType' => 'Тип отчета',
            'longAsset' => 'Долгосрочные активы, т.р.',
            'shortAsset' => 'Краткосрочные активы, т.р.',
            'longDebt' => 'Долгосрочные долги, т.р.',
            'shortDebt' => 'Краткосрочные долги, т.р.',
            'capital' => 'Капитал, т.р.',
        ];
    }

    public static function createOrUpdate(
        Issuer $issuer,

        DateTimeImmutable $date,
        float $longAsset,
        float $shortAsset,
        float $longDebt,
        float $shortDebt,
        float $capital,

        FinanceTermType $termType = FinanceTermType::year,
    ): self {
        $self = self::findOne([
            'issuer_id' => $issuer->id,
            'year' => $date->format('Y'),
            '_termType' => $termType->value,
        ]) ?: new self([
            'issuer_id' => $issuer->id,
            'year' => $date->format('Y'),
            '_termType' => $termType->value,
        ]);

        $self->longAsset = $longAsset;
        $self->shortAsset = $shortAsset;
        $self->longDebt = $longDebt;
        $self->shortDebt = $shortDebt;
        $self->capital = $capital;

        $self->renewLastApiUpdateDate();

        return $self;
    }

    public function getYearDto(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->year);
    }

    public function getTermType(): FinanceTermType
    {
        return FinanceTermType::from($this->_termType);
    }

    public function getIssuer(): ActiveQuery
    {
        return $this->hasOne(Issuer::class, ['id' => 'issuer_id']);
    }
}
