<?php

namespace src\Entity\Issuer\IssuerEvent;

use DateTimeImmutable;
use lib\Database\ApiFetchedActiveRecord;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\PayerIdentificationNumber;
use yii\db\ActiveQuery;

/**
 * @property string $_pid;
 * @property PayerIdentificationNumber $pid;
 *
 * @property string $_eventDate;
 * @property \DateTimeImmutable $eventDate;
 *
 * @property string $eventName;
 */
class IssuerEvent extends ApiFetchedActiveRecord
{
    public const array IMPORTANT_EVENTS = [
        'руководителя',
        'задолженности',
        'банкротом',
        'банкрот',
        'ликвидацией',
        'ликвидация',
        'ликвидации',
    ];

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
        PayerIdentificationNumber $pid,
        string $eventName,
        DateTimeImmutable $eventDate,
    ): self {
        $self = self::findOne([
            '_pid' => $pid->id,
            'eventName' => $eventName,
            '_eventDate' => $eventDate->format(DATE_ATOM),
        ]) ?: new self([
            '_pid' => $pid->id,
            'eventName' => $eventName,
            '_eventDate' => $eventDate->format(DATE_ATOM),
        ]);

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
