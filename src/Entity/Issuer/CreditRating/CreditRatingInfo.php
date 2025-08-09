<?php

namespace src\Entity\Issuer\CreditRating;

use DateTimeImmutable;
use lib\Database\ApiFetchedActiveRecord;
use lib\Helper\TrimHelper;
use src\Entity\Issuer\Issuer;
use src\Integration\Bik\CreditRating\BikCreditRatingForecast;
use yii\db\ActiveQuery;

/**
 * @inheritDoc
 * @property string $issuerName
 * @property int $issuerId
 * @property Issuer $issuer
 *
 * @property ?BikCreditRatingForecast $forecast
 * @property ?string $_forecast
 *
 * @property string $_assignmentDate;
 * @property \DateTimeImmutable $assignmentDate;
 *
 * @property string $_lastUpdateDate Дата последнего рейтингового действия
 * @property \DateTimeImmutable $lastUpdateDate Дата последнего рейтингового действия
 *
 * @property string $_rating;
 * @property CreditRating $rating
 *
 * @property string $pressReleaseLink
 */
class CreditRatingInfo extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return 'issuer_credit_rating_info';
    }

    public function attributeLabels(): array
    {
        return [
            '_rating' => 'Рейтинг',
            '_assignmentDate' => 'Дата присвоения',
            '_forecast' => 'Прогноз',
            '_lastUpdateDate' => 'Последнее обновление от BIK',
            'pressReleaseLink' => 'Пресс релиз',
            'issuerName' => 'Эмитент',
        ];
    }

    public static function createOrUpdate(
        string $issuerName,
        CreditRating $rating,
        ?BikCreditRatingForecast $forecast,
        DateTimeImmutable $assignmentDate,
        DateTimeImmutable $lastUpdateDate,
        string $pressReleaseLink,
    ): self {
        $self = self::findOne(['issuerName' => $issuerName]) ?: new self();
        $self->updateInfo(
            issuerName: $issuerName,
            rating: $rating,
            forecast: $forecast,
            assignmentDate: $assignmentDate,
            lastUpdateDate: $lastUpdateDate,
            pressReleaseLink: $pressReleaseLink,
        );

        return $self;
    }

    public function updateInfo(
        string $issuerName,
        CreditRating $rating,
        ?BikCreditRatingForecast $forecast,
        DateTimeImmutable $assignmentDate,
        DateTimeImmutable $lastUpdateDate,
        string $pressReleaseLink,
    ): void {
        $this->issuerName = $issuerName;
        $this->_rating = $rating->value;
        $this->_forecast = $forecast?->value;
        $this->_assignmentDate = $assignmentDate->format(DATE_ATOM);
        $this->_lastUpdateDate = $lastUpdateDate->format(DATE_ATOM);
        $this->pressReleaseLink = TrimHelper::trim($pressReleaseLink);

        $this->renewLastApiUpdateDate();
    }

    public function getRating(): CreditRating
    {
        return CreditRating::from($this->_rating);
    }

    public function getForecast(): ?BikCreditRatingForecast
    {
        return BikCreditRatingForecast::fromString($this->_forecast);
    }

    public function getAssignmentDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(DATE_ATOM, $this->_assignmentDate);
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
