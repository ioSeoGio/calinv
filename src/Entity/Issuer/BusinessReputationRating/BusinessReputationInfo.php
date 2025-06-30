<?php

namespace src\Entity\Issuer\BusinessReputationRating;

use DateTimeImmutable;
use lib\Helper\TrimHelper;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\PayerIdentificationNumber;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property string $_pid;
 * @property PayerIdentificationNumber $pid;
 *
 * @property string $issuerName;
 *
 * @property string $_lastUpdateDate;
 * @property \DateTimeImmutable $lastUpdateDate;
 *
 * @property string $_rating;
 * @property IssuerBusinessReputation $rating;
 *
 * @property string $pressReleaseLink;
 */
class BusinessReputationInfo extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'business_reputation_info';
    }

    public function attributeLabels(): array
    {
        return [
            'rating' => 'BIK рейтинг деловой репутации',
            'pid' => 'УНП',
        ];
    }

    public static function make(
        string $issuerName,
        PayerIdentificationNumber $pid,
        IssuerBusinessReputation $rating,
        DateTimeImmutable $lastUpdateDate,
        string $pressReleaseLink,
    ): self {
        return new self([
            'issuerName' => $issuerName,
            '_pid' => $pid->id,
            '_rating' => $rating->value,
            '_lastUpdateDate' => $lastUpdateDate->format(DATE_ATOM),
            'pressReleaseLink' => TrimHelper::trim($pressReleaseLink),
        ]);
    }

    public static function findByPid(PayerIdentificationNumber $pid): ?self
    {
        return self::findOne(['_pid' => $pid->id]);
    }

    public function updateInfo(
        string $issuerName,
        IssuerBusinessReputation $rating,
        DateTimeImmutable $lastUpdateDate,
        string $pressReleaseLink,
    ): void {
        $this->issuerName = $issuerName;
        $this->_rating = $rating->value;
        $this->_lastUpdateDate = $lastUpdateDate->format(DATE_ATOM);
        $this->pressReleaseLink = TrimHelper::trim($pressReleaseLink);
    }

    public function getPid(): PayerIdentificationNumber
    {
        return new PayerIdentificationNumber($this->_pid);
    }

    public function getRating(): IssuerBusinessReputation
    {
        return IssuerBusinessReputation::from($this->_rating);
    }

    public function getLastUpdateDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(DATE_ATOM, $this->_lastUpdateDate);
    }

    public function getIssuer(): ActiveQuery
    {
        return $this->hasOne(BusinessReputationInfo::class, ['_pid' => '_pid']);

        // @todo придумать как подтягивать по имени если не нашло по УНП
        return Issuer::find()
            ->orWhere(['name' => $this->issuerName])
            ->orWhere(['_pid' => $this->_pid]);
    }
}
