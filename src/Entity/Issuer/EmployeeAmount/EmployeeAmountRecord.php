<?php

namespace src\Entity\Issuer\EmployeeAmount;

use lib\Database\ApiFetchedActiveRecord;
use src\Entity\Issuer\Issuer;
use yii\db\ActiveQuery;

/**
 * @inheritDoc
 * @property int $issuerId;
 * @property Issuer $issuer;
 *
 * @property int $amount;
 * @property int $year;
 *
 * @property string $_date;
 * @property \DateTimeImmutable $date;
 */
class EmployeeAmountRecord extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return 'issuer_employee_amount';
    }

    public function attributeLabels(): array
    {
        return [
            'amount' => 'Среднесписочная численность работников',
            '_date' => 'Дата',
        ];
    }

    public static function createOrUpdate(
        Issuer $issuer,
        int $amount,
        int $year,
        \DateTimeImmutable $date,
    ): self {
        $self = self::findOne([
            'issuerId' => $issuer->id,
            '_date' => $date->format(DATE_ATOM),
        ]) ?: new self();

        $self->issuerId = $issuer->id;
        $self->amount = $amount;
        $self->year = $year;
        $self->_date = $date->format(DATE_ATOM);

        $self->renewLastApiUpdateDate();

        return $self;
    }

    public function getIssuer(): ActiveQuery
    {
        return $this->hasOne(Issuer::class, ['id' => 'issuerId']);
    }
}
