<?php

namespace src\Entity\Issuer\BusinessReputationRating;

use DateInterval;
use DateTimeImmutable;
use lib\Database\ApiFetchedActiveRecord;
use lib\Helper\TrimHelper;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\PayerIdentificationNumber;
use yii\db\ActiveQuery;

/**
 * @inheritDoc
 * @property int $id
 * @property string $_pid
 * @property PayerIdentificationNumber $pid
 * @property Issuer $issuer
 * @property int $issuerId
 *
 * @property string $issuerName
 *
 * @property string $_lastUpdateDate Дата последнего рейтингово действия
 * @property \DateTimeImmutable $lastUpdateDate Дата последнего рейтингово действия
 *
 * @property string $_rating;
 * @property IssuerBusinessReputation $rating
 *
 * @property string $pressReleaseLink
 */
class BusinessReputationInfo extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return 'issuer_business_reputation_info';
    }

    public function attributeLabels(): array
    {
        return [
            '_rating' => 'Рейтинг',
            '_lastUpdateDate' => 'Последнее обновление от BIK',
            'pressReleaseLink' => 'Пресс релиз',
            '_pid' => 'УНП',
            'issuerName' => 'Эмитент',
        ];
    }

    public static function createOrUpdate(
        ?Issuer $issuer,
        string $issuerName,
        PayerIdentificationNumber $pid,
        IssuerBusinessReputation $rating,
        DateTimeImmutable $lastUpdateDate,
        string $pressReleaseLink,
    ): self {
        $self = self::findOne(['_pid' => $pid->id]) ?: new self(['_pid' => $pid->id]);
        $self->issuerId = $issuer?->id;
        $self->updateInfo(
            issuerName: $issuerName,
            rating: $rating,
            lastUpdateDate: $lastUpdateDate,
            pressReleaseLink: $pressReleaseLink,
        );

        return $self;
    }

    public static function findByIssuerName(string $issuerName): ?self
    {
        return self::findOne(['issuerName' => $issuerName]);
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

        $this->renewLastApiUpdateDate();
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
        return $this->hasOne(Issuer::class, ['id' => 'issuerId']);
    }
}
