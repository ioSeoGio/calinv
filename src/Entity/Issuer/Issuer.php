<?php

namespace src\Entity\Issuer;

use src\Action\Issuer\IssuerCreateForm;
use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfo;
use src\Entity\Share\Share;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property ?string $name
 * @property BusinessReputationInfo $businessReputationInfo
 *
 * @property array $fullnessState
 *
 * @property IssuerLegalStatus $legalStatus
 * @property string $_legalStatus
 *
 * @property PayerIdentificationNumber $pid
 * @property string $_pid
 */
class Issuer extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'issuer';
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Наименование',
            'fullnessState' => 'Заполненность',
            'expressRating' => 'Экспресс рейтинг',
            '_pid' => 'УНП',
        ];
    }

    public static function make(
        PayerIdentificationNumber $pid,
    ): self {
        return new self([
            '_pid' => $pid->id,
            'fullnessState' => [IssuerFullnessState::initial->value],
        ]);
    }

    public static function findByIssuerName(string $issuerName): ?self
    {
        return self::findOne(['name' => $issuerName]);
    }

    public function addFullnessState(IssuerFullnessState $state): void
    {
        $this->fullnessState = array_merge($this->fullnessState, [$state->value]);;
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

    public function getBusinessReputationInfo(): ActiveQuery
    {
        return $this->hasOne(BusinessReputationInfo::class, ['_pid' => '_pid']);

        // @todo придумать как подтягивать по имени если не нашло по УНП
        return BusinessReputationInfo::find()
            ->orWhere(['issuerName' => $this->name])
            ->orWhere(['_pid' => $this->_pid]);
    }
}
