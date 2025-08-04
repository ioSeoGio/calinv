<?php

namespace src\Action\Issuer\FinancialReport\ProfitLossReport;

use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;
use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReport;
use src\Entity\Issuer\Issuer;
use yii\base\Model;

class ProfitLossReportCreateForm extends Model
{
    public int $issuerId;
    public $year;

    public $_010;
    public $_090;
    public $_210;
    public $_240;

    public function __construct(Issuer $issuer, ?int $year, $config = [])
    {
        $this->year = $year ?: (new \DateTimeImmutable('last year'))->format('Y');
        $this->issuerId = $issuer->id;

        $report = $year === null ? null : ProfitLossReport::getOneByCriteria([
            'issuer_id' => $issuer->id,
            '_year' => $this->year,
        ]);

        if ($report !== null) {
            $this->_010 = $report->_010;
            $this->_090 = $report->_090;
            $this->_210 = $report->_210;
            $this->_240 = $report->_240;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['issuerId'], 'required'],
            [['year'], 'required'],
            [['year'], 'date', 'format' => 'php:Y'],

            [[
                '_010',
                '_090',
                '_210',
                '_240',
            ], 'double'],
            [[
                '_010',
                '_090',
                '_210',
                '_240',
            ], 'required', 'message' => 'Заполните.'],
        ];
    }
}