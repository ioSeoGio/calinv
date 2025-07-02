<?php

namespace src\Entity\Issuer;

use lib\Database\ApiFetchedActiveRecord;
use lib\Database\BaseActiveRecord;
use src\Entity\Issuer\AddressInfo\AddressInfo;
use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfo;
use src\Entity\Issuer\EsgRating\EsgRatingInfo;
use src\Entity\Issuer\TypeOfActivity\TypeOfActivity;
use src\Entity\Share\Share;
use yii\db\ActiveQuery;

/**
 * @inheritDoc
 * @property int $id
 * @property ?string $name
 *
 * @property ?BusinessReputationInfo $businessReputationInfo
 * @property ?AddressInfo $addressInfo
 * @property ?EsgRatingInfo $esgRatingInfo
 *
 * @property array $fullnessState
 *
 * @property IssuerLegalStatus $legalStatus
 * @property string $_legalStatus
 *
 * @property PayerIdentificationNumber $pid
 * @property string $_pid
 */
class Issuer extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return 'issuer';
    }

    public function attributeLabels(): array
    {
        return [
            '_legalStatus' => 'Статус',
            'name' => 'Наименование',
            'fullnessState' => 'Заполненность',
            'expressRating' => 'Экспресс рейтинг',
            '_pid' => 'УНП',
        ];
    }

    public static function createOrGet(PayerIdentificationNumber $pid): Issuer
    {
        return self::findOne(['_pid' => $pid->id]) ?: new self([
            '_pid' => $pid->id,
            'fullnessState' => [IssuerFullnessState::initial],
        ]);
    }

    public function updateInfo(
        string $name,
        IssuerLegalStatus $legalStatus,
    ): self {
        $this->_legalStatus = $legalStatus->value;
        $this->name = $name;

        $this->renewLastApiUpdateDate();

        return $this;
    }

    public static function findByIssuerName(string $issuerName): ?self
    {
        return self::findOne(['name' => $issuerName]);
    }

    public function addFullnessState(IssuerFullnessState $state): void
    {
        $this->fullnessState = array_merge($this->fullnessState, [$state->value]);;
    }

    public function setFullnessState(IssuerFullnessState ...$states): void
    {
        $this->fullnessState = $states;
    }

    public function updateName(string $name): void
    {
        $this->name = $name;
    }

    public function updateLegalStatus(IssuerLegalStatus $status): void
    {
        $this->_legalStatus = $status->value;
    }

    public function getLegalStatus(): IssuerLegalStatus
    {
        return IssuerLegalStatus::from($this->_legalStatus);
    }

    public function getPid(): PayerIdentificationNumber
    {
        return new PayerIdentificationNumber($this->_pid);
    }

    public function getShares(): ActiveQuery
    {
        return $this->hasMany(Share::class, ['issuer_id' => 'id']);
    }

    public function getTypeOfActivity(): ActiveQuery
    {
        return $this->hasOne(TypeOfActivity::class, ['_pid' => '_pid']);
    }

    public function getAddressInfo(): ActiveQuery
    {
        return $this->hasOne(AddressInfo::class, ['_pid' => '_pid']);
    }

    public function getEsgRatingInfo(): ActiveQuery
    {
        // @todo придумать как подтягивать по имени если не нашло по УНП
        return $this->hasOne(EsgRatingInfo::class, ['_pid' => '_pid']);
    }

    public function getBusinessReputationInfo(): ActiveQuery
    {
        return $this->hasOne(BusinessReputationInfo::class, ['_pid' => '_pid']);

        // @todo придумать как подтягивать по имени если не нашло по УНП
        return BusinessReputationInfo::find()
            ->orWhere(['issuerName' => $this->name])
            ->orWhere(['_pid' => $this->_pid]);
    }
}
