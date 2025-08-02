<?php

namespace src\Entity\Issuer\AdditionalInfo;

use lib\Database\ApiFetchedActiveRecord;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\PayerIdentificationNumber;
use yii\db\ActiveQuery;

/**
 * @inheritDoc
 * @property string $issuerId
 * @property Issuer $issuer
 *
 * @property \DateTimeImmutable $beginDate Дата принятия решения о ликвидации
 * @property string $_beginDate Дата принятия решения о ликвидации
 *
 * @property \DateTimeImmutable $publicationDate Дата принятия решения о ликвидации
 * @property string $_publicationDate Дата принятия решения о ликвидации
 *
 * @property ?\DateTimeImmutable $currentStatusClaimDate Дата принятия решения о ликвидации
 * @property ?string $_currentStatusClaimDate Дата принятия решения о ликвидации
 *
 * @property string $liquidationDecisionNumber Номер решения о ликвидации
 * @property string $liquidatorName Название компании-ликвидатора
 * @property string $liquidatorAddress
 * @property string $liquidatorPhone
 * @property int $periodForAcceptingClaimsInMonths Период принятия претензий в месяцах с момента опубликования
 * @property string $status
 */
class IssuerLiquidationInfo extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return 'issuer_liquidation_info';
    }

    public function attributeLabels(): array
    {
        return [
            '_pid' => 'УНП',
            'liquidationDecisionNumber' => 'Номер решения о ликвидации',
            'status' => 'Статус процедуры',
            '_beginDate' => 'Дата начала процедуры',
            '_publicationDate' => 'Дата опубликования',
            'periodForAcceptingClaimsInMonths' => 'Период принятия претензий с момент опубликования',
        ];
    }

    public static function createOrUpdate(
        Issuer $issuer,

        \DateTimeImmutable $beginDate,
        \DateTimeImmutable $publicationDate,
        ?\DateTimeImmutable $currentStatusClaimDate,
        string $liquidationDecisionNumber,
        string $liquidatorName,
        string $liquidatorAddress,
        string $liquidatorPhone,
        int $periodForAcceptingClaimsInMonths,
        string $status,
    ): self {
        $self = self::findOne(['issuerId' => $issuer->id]) ?: new self();
        $self->issuerId = $issuer->id;

        $self->_beginDate = $beginDate->format(DATE_ATOM);
        $self->_publicationDate = $publicationDate->format(DATE_ATOM);
        $self->_currentStatusClaimDate = $currentStatusClaimDate->format('Y-m-d') === '-0001-11-30'
            ? null
            : $currentStatusClaimDate->format(DATE_ATOM);

        $self->liquidationDecisionNumber = $liquidationDecisionNumber;
        $self->liquidatorName = $liquidatorName;
        $self->liquidatorAddress = $liquidatorAddress;
        $self->liquidatorPhone = $liquidatorPhone;
        $self->periodForAcceptingClaimsInMonths = $periodForAcceptingClaimsInMonths;
        $self->status = $status;

        $self->renewLastApiUpdateDate();

        return $self;
    }

    public function getPublicationDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->_publicationDate);
    }

    public function getIssuer(): ActiveQuery
    {
        return $this->hasOne(Issuer::class, ['id' => 'issuerId']);
    }
}
