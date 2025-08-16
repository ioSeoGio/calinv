<?php

namespace src\Entity\Issuer\FinanceReport;

use DateTimeImmutable;
use lib\Database\ApiFetchedActiveRecord;
use src\Entity\Issuer\Issuer;
use yii\db\ActiveQuery;

/**
 * @inheritDoc
 *
 * @property int $issuerId
 * @property Issuer $issuer
 *
 * @property bool $hasAccountingBalance
 * @property bool $hasProfitLossReport
 * @property bool $hasCapitalChangeReport
 * @property bool $hasCashFlowReport
 *
 * @property string $_year;
 * @property \DateTimeImmutable $year;
 */
class AvailableFinancialReportData extends ApiFetchedActiveRecord
{
    public static function tableName(): string
    {
        return '{{%available_financial_report_data}}';
    }

    public function attributeLabels(): array
    {
        return [
            '_year' => 'Год',
        ];
    }

    public static function createOrUpdate(
        Issuer $issuer,
        DateTimeImmutable $year,
        bool $hasCapitalChangeReport,
        bool $hasCashFlowReport,
        bool $hasProfitLossReport,
        bool $hasAccountingBalance,
    ): self {
        $self = self::findOne([
            'issuerId' => $issuer->id,
            '_year' => $year->format('Y'),
        ]) ?: new self([
            'issuerId' => $issuer->id,
            '_year' => $year->format('Y'),
        ]);

        $self->hasCapitalChangeReport = $hasCapitalChangeReport;
        $self->hasCashFlowReport = $hasCashFlowReport;
        $self->hasProfitLossReport = $hasProfitLossReport;
        $self->hasAccountingBalance = $hasAccountingBalance;

        $self->renewLastApiUpdateDate();

        return $self;
    }

    public function getYear(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('Y', $this->_year);
    }

    public function getIssuer(): ActiveQuery
    {
        return $this->hasOne(Issuer::class, ['id' => 'issuerId']);
    }
}
