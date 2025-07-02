<?php

namespace src\Entity\Issuer\IssuerEvent;

use DateTimeImmutable;
use lib\Database\ApiFetchedActiveRecord;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\PayerIdentificationNumber;
use yii\db\ActiveQuery;

/**
 * @property string $externalId;
 * @property string $_pid;
 * @property PayerIdentificationNumber $pid;
 *
 * @property string $_eventDate;
 * @property \DateTimeImmutable $eventDate;
 *
 * @property ?string $_eventCancelDate;
 * @property ?\DateTimeImmutable $eventCancelDate;
 *
 * @property string $currentAccountingAgency;
 * @property ?string $decideAccountingAgency;
 * @property ?string $reason;
 * @property string $eventName;
 */
class IssuerEvent extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return 'issuer_event';
    }

    public function attributeLabels(): array
    {
        return [
            '_pid' => 'УНП',
            'eventName' => 'Событие',
            '_eventDate' => 'Дата события',
        ];
    }

    public static function createOrUpdate(
        string $externalId,
        PayerIdentificationNumber $pid,
        DateTimeImmutable $eventDate,
        ?DateTimeImmutable $eventCancelDate,
        string $currentAccountingAgency,
        ?string $decideAccountingAgency,
        ?string $reason,
        string $eventName,
    ): self {
        $self = self::findOne([
            '_pid' => $pid->id,
            'externalId' => $externalId,
        ]) ?: new self([
            '_pid' => $pid->id,
            'externalId' => $externalId,
        ]);

        $self->_eventDate = $eventDate->format(DATE_ATOM);
        $self->_eventCancelDate = $eventCancelDate?->format(DATE_ATOM);
        $self->currentAccountingAgency = $currentAccountingAgency;
        $self->decideAccountingAgency = $decideAccountingAgency;
        $self->reason = $reason;
        $self->eventName = $eventName;
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
