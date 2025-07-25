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
 * @property string $_pid;
 * @property PayerIdentificationNumber $pid;
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
            '_rating' => 'BIK рейтинг деловой репутации',
            '_lastUpdateDate' => 'Дата последнего обновления',
            'pressReleaseLink' => 'Пресс релиз',
            '_pid' => 'УНП',
            'issuerName' => 'Эмитент',
        ];
    }

    public static function findByIssuerName(string $issuerName): ?self
    {
        return self::findOne(['issuerName' => $issuerName]);
    }

    public function updateInfo(
        ?PayerIdentificationNumber $pid,
        BikEsgForecast $forecast,
        EsgRating $rating,
        EsgIssuerCategory $category,
        \DateTimeImmutable $assignmentDate,
        \DateTimeImmutable $lastUpdateDate,
        string $pressReleaseLink,
    ): void {
        $this->_pid = $pid ? $pid->id : $this->_pid;
        $this->_forecast = $forecast->value;
        $this->_rating = $rating->value;
        $this->_category = $category->value;
        $this->_assignmentDate = $assignmentDate->format(DATE_ATOM);
        $this->_lastUpdateDate = $lastUpdateDate->format(DATE_ATOM);
        $this->pressReleaseLink = $pressReleaseLink;

        $this->renewLastApiUpdateDate();
    }

    public static function make(
        ?PayerIdentificationNumber $pid,
        string $issuerName,
        BikEsgForecast $forecast,
        EsgRating $rating,
        EsgIssuerCategory $category,
        \DateTimeImmutable $assignmentDate,
        \DateTimeImmutable $lastUpdateDate,
        string $pressReleaseLink,
    ): self {
        $self = new self(['issuerName' => $issuerName]);
        $self->updateInfo(
            pid: $pid,
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

    public function getIssuedDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(DATE_ATOM, $this->_assignmentDate);
    }

    public function getIssuer(): ActiveQuery
    {
        return $this->hasOne(Issuer::class, ['_pid' => '_pid']);

        // @todo придумать как подтягивать по имени если не нашло по УНП
        return Issuer::find()
            ->orWhere(['name' => $this->issuerName])
            ->orWhere(['_pid' => $this->_pid]);
    }
}
