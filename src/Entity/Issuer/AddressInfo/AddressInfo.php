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
 * @property string $country;
 * @property string $settlementType;
 * @property string $settlementName;
 * @property ?string $placeType;
 * @property string $placeName;
 * @property string $houseNumber;
 * @property string $roomType;
 * @property string $roomNumber;
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
        string $country,
        string $settlementType,
        string $settlementName,
        string $placeType,
        string $placeName,
        string $houseNumber,
        ?string $roomType,
        ?string $roomNumber,
        ?string $email,
        ?string $site,
        ?string $phoneNumbers,
    ): self {
        $self = self::findOne(['_pid' => $pid->id]) ?:  new self();

        $self->_pid = $pid->id;
        $self->country = $country;
        $self->settlementType = $settlementType;
        $self->settlementName = $settlementName;
        $self->placeType = $placeType;
        $self->placeName = $placeName;
        $self->houseNumber = $houseNumber;
        $self->roomType = $roomType;
        $self->roomNumber = $roomNumber;
        $self->email = $email;
        $self->site = $site;
        $self->phones = $phoneNumbers;

        $self->renewLastApiUpdateDate();

        return $self;
    }

    public function getFullAddress(): string
    {
        return implode(' ', [
            $this->settlementType,
            $this->settlementName,
            $this->placeType,
            $this->placeName,
            $this->houseNumber,
            $this->roomType,
            $this->roomNumber
        ]);
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
