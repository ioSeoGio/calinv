<?php

namespace src\Action\Issuer\FinancialReport\CashFlowReport;

use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;
use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReport;
use src\Entity\Issuer\Issuer;
use yii\base\Model;

class CashFlowReportCreateForm extends Model
{
    public int $issuerId;
    public $year;

    public $_020;
    public $_030;
    public $_040;
    public $_070;
    public $_080;
    public $_090;
    public $_100;
    public $_110;

    public function __construct(Issuer $issuer, ?int $year, $config = [])
    {
        $this->year = $year ?: (new \DateTimeImmutable('last year'))->format('Y');
        $this->issuerId = $issuer->id;

        $report = $year === null ? null : ProfitLossReport::getOneByCriteria([
            'issuer_id' => $issuer->id,
            '_year' => $this->year,
        ]);

        if ($report !== null) {
            $this->_020 = $report->_020;
            $this->_030 = $report->_030;
            $this->_040 = $report->_040;
            $this->_070 = $report->_070;
            $this->_080 = $report->_080;
            $this->_090 = $report->_090;
            $this->_100 = $report->_100;
            $this->_110 = $report->_110;
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
                '_020',
                '_030',
                '_040',
                '_070',
                '_080',
                '_090',
                '_100',
                '_110',
            ], 'double'],
            [[
                '_020',
                '_030',
                '_040',
                '_080',
                '_090',
                '_100',
                '_110',
            ], 'required', 'message' => 'Заполните.'],
        ];
    }
}