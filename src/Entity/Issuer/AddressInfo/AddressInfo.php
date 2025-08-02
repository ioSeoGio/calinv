<?php

namespace src\Entity\Issuer\AddressInfo;

use lib\Database\ApiFetchedActiveRecord;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\PayerIdentificationNumber;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @inheritDoc
 * @property string $_pid;
 * @property PayerIdentificationNumber $pid;
 *
 * @property string $fullAddress;
 *
 * @property ?string $email;
 * @property ?string $site;
 * @property ?string $phones;
 */
class AddressInfo extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return 'issuer_address_info';
    }

    public function attributeLabels(): array
    {
        return [
            '_pid' => 'УНП',
            'fullAddress' => 'Полный адрес',
            'site' => 'Сайт',
            'email' => 'email',
            'phones' => 'Телефон',
        ];
    }

    public static function createOrUpdate(
        PayerIdentificationNumber $pid,
        string $fullAddress,
        ?string $phoneNumbers,
        ?string $email = null,
        ?string $site = null,
    ): self {
        $self = self::findOne(['_pid' => $pid->id]) ?: new self();

        $self->_pid = $pid->id;
        $self->fullAddress = $fullAddress;
        $self->email = $email;
        $self->site = $site;
        $self->phones = $phoneNumbers;

        $self->renewLastApiUpdateDate();

        return $self;
    }

    public function updateFields(
        string $fullAddress,
        ?string $phoneNumbers,
        ?string $email,
        ?string $site,
    ): self {
        $this->fullAddress = $fullAddress;
        $this->site = $site;
        $this->email = $email;

        if ($this->phones === null) {
            $this->phones = $phoneNumbers;
        } elseif ($phoneNumbers !== null && !str_contains($this->phones, $phoneNumbers)) {
            $this->phones = $this->phones !== null
                ? $this->phones . ' ' . $phoneNumbers
                : $phoneNumbers;
        }

        return $this;
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
