<?php

namespace src\Entity\Issuer\UnreliableSupplier;

use lib\Database\ApiFetchedActiveRecord;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\PayerIdentificationNumber;
use yii\db\ActiveQuery;

/**
 * @inheritDoc
 * @property string $_pid
 * @property PayerIdentificationNumber $pid
 *
 * @property string $uuid api uuid
 * @property string $chainUuid api говно какое-то
 * @property string $authorUuid Автор внесения
 * @property string $authorInitials Автор внесения
 * @property string $state Состояние записи
 * @property string $issuerName
 * @property string $issuerAddress
 * @property string $registrationNumber Регистрационный номер записи
 *
 * @property string $_addDate Дата внесения в список
 * @property \DateTimeImmutable $addDate Дата внесения в список
 *
 * @property ?string $_deleteDate Дата исключения из списка
 * @property ?\DateTimeImmutable $deleteDate Дата исключения из списка
 *
 * @property string $reason Причина внесения в список
 *
 * @property ?Issuer $issuer
 */
class UnreliableSupplier extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return 'issuer_unreliable_supplier';
    }

    public function attributeLabels(): array
    {
        return [
            '_pid' => 'УНП',
            'issuerName' => 'Эмитент',
            'reason' => 'Причина внесения в список',
            '_addDate' => 'Дата внесения',
        ];
    }

    public static function createOrUpdate(
        PayerIdentificationNumber $pid,
        string $uuid,
        string $chainUuid,
        string $authorUuid,
        string $authorInitials,
        string $state,
        string $issuerName,
        string $issuerAddress,
        string $registrationNumber,
        string $addDate,
        ?string $deleteDate,
        string $reason,
    ): self {
        $self = self::findOne([
            '_pid' => $pid->id,
            'uuid' => $uuid,
        ]) ?: new self([
            '_pid' => $pid->id,
            'uuid' => $uuid,
        ]);

        $self->chainUuid = $chainUuid;
        $self->authorUuid = $authorUuid;
        $self->authorInitials = $authorInitials;
        $self->state = $state;
        $self->issuerName = $issuerName;
        $self->issuerAddress = $issuerAddress;
        $self->registrationNumber = $registrationNumber;
        $self->_addDate = $addDate;
        $self->reason = $reason;
        $self->_deleteDate = $deleteDate;

        $self->renewLastApiUpdateDate();

        return $self;
    }

    public function getAddDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->_addDate);
    }

    public function getDeleteDate(): ?\DateTimeImmutable
    {
        return $this->_deleteDate ? new \DateTimeImmutable($this->_deleteDate) : null;
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
