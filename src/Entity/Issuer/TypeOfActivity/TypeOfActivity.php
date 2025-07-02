<?php

namespace src\Entity\Issuer\TypeOfActivity;

use lib\Database\ApiFetchedActiveRecord;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\PayerIdentificationNumber;
use yii\db\ActiveQuery;

/**
 * @inheritDoc
 * @property string $_pid;
 * @property PayerIdentificationNumber $pid;
 *
 * @property string $_activityFromDate;
 * @property \DateTimeImmutable $activityFromDate;
 *
 * @property ?string $_activityToDate;
 * @property ?\DateTimeImmutable $activityToDate;
 *
 * @property bool $isActive
 *
 * @property string $code ОКЭД
 * @property string $name название вида деятельности
 */
class TypeOfActivity extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return 'issuer_type_of_activity';
    }

    public function attributeLabels(): array
    {
        return [
            '_pid' => 'УНП',
        ];
    }

    public static function createOrUpdate(
        PayerIdentificationNumber $pid,
        \DateTimeImmutable $activityFromDate,
        ?\DateTimeImmutable $activityToDate,
        bool $isActive,
        string $code,
        string $name,
    ): self {
        $self = self::findOne(['_pid' => $pid->id]) ?:  new self();

        $self->_pid = $pid->id;
        $self->_activityFromDate = $activityFromDate->format(DATE_ATOM);
        $self->_activityToDate = $activityToDate?->format(DATE_ATOM);
        $self->isActive = $isActive;
        $self->code = $code;
        $self->name = $name;
        $self->renewLastApiUpdateDate();

        return $self;
    }

    public static function findByPid(PayerIdentificationNumber $pid): ?self
    {
        return self::findOne(['_pid' => $pid->id]);
    }

    public function getPid(): PayerIdentificationNumber
    {
        return new PayerIdentificationNumber($this->_pid);
    }

    public function getIssuer(): ActiveQuery
    {
        return $this->hasOne(Issuer::class, ['_pid' => '_pid']);
    }
}
