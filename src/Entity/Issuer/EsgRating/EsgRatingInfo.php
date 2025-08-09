<?php

namespace src\Entity\Issuer\EsgRating;

use DateTimeImmutable;
use lib\Database\ApiFetchedActiveRecord;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Bik\EsgRating\BikEsgForecast;
use src\Integration\Bik\EsgRating\EsgIssuerCategory;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @inheritDoc
 *
 * @property int $issuerId
 * @property Issuer $issuer
 *
 * @property string $issuerName;
 *
 * @property BikEsgForecast $forecast;
 * @property string $_forecast;
 *
 * @property string $_rating;
 * @property EsgRating $rating;
 *
 * @property EsgIssuerCategory $category;
 * @property string $_category;
 *
 * @property string $_assignmentDate;
 * @property \DateTimeImmutable $assignmentDate;
 *
 * @property string $_lastUpdateDate;
 * @property \DateTimeImmutable $lastUpdateDate;
 *
 * @property string $pressReleaseLink;
 */
class EsgRatingInfo extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return '{{%issuer_esg_rating_info}}';
    }

    public function attributeLabels(): array
    {
        return [
            '_rating' => 'Рейтинг',
            '_assignmentDate' => 'Дата присвоения',
            '_lastUpdateDate' => 'Последнее обновление от BIK',
            'pressReleaseLink' => 'Пресс релиз',
            'issuerName' => 'Эмитент',
        ];
    }

    public function updateInfo(
        BikEsgForecast $forecast,
        EsgRating $rating,
        EsgIssuerCategory $category,
        \DateTimeImmutable $assignmentDate,
        \DateTimeImmutable $lastUpdateDate,
        string $pressReleaseLink,
    ): void {
        $this->_forecast = $forecast->value;
        $this->_rating = $rating->value;
        $this->_category = $category->value;
        $this->_assignmentDate = $assignmentDate->format(DATE_ATOM);
        $this->_lastUpdateDate = $lastUpdateDate->format(DATE_ATOM);
        $this->pressReleaseLink = $pressReleaseLink;

        $this->renewLastApiUpdateDate();
    }

    public static function createOrUpdate(
        string $issuerName,
        BikEsgForecast $forecast,
        EsgRating $rating,
        EsgIssuerCategory $category,
        \DateTimeImmutable $assignmentDate,
        \DateTimeImmutable $lastUpdateDate,
        string $pressReleaseLink,
    ): self {
        $self = self::findOne(['issuerName' => $issuerName])
            ?: new self(['issuerName' => $issuerName]);

        $self->updateInfo(
            forecast: $forecast,
            rating: $rating,
            category: $category,
            assignmentDate: $assignmentDate,
            lastUpdateDate: $lastUpdateDate,
            pressReleaseLink: $pressReleaseLink,
        );

        return $self;
    }

    public function getCategory(): EsgIssuerCategory
    {
        return EsgIssuerCategory::from($this->_category);
    }

    public function getRating(): EsgRating
    {
        return EsgRating::from($this->_rating);
    }

    public function getLastUpdateDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(DATE_ATOM, $this->_lastUpdateDate);
    }

    public function getAssignmentDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(DATE_ATOM, $this->_assignmentDate);
    }

    public function getIssuer(): ActiveQuery
    {
        return $this->hasOne(Issuer::class, ['id' => 'issuerId']);
    }
}
