<?php

namespace src\Action\Issuer\FinancialReport\CashFlowReport;

use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;
use src\Entity\Issuer\FinanceReport\CashFlowReport\CashFlowReport;
use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReport;
use src\Entity\Issuer\Issuer;
use yii\base\Model;

class CashFlowReportCreateForm extends Model
{
    public int $issuerId;
    public $year;

    public $_020;
    public $_021;
    public $_022;
    public $_023;
    public $_024;

    public $_030;
    public $_031;
    public $_032;
    public $_033;
    public $_040;

    public $_050;
    public $_051;
    public $_052;
    public $_053;
    public $_054;
    public $_055;

    public $_060;
    public $_061;
    public $_062;
    public $_063;
    public $_064;
    public $_070;

    public $_080;
    public $_081;
    public $_082;
    public $_083;
    public $_084;

    public $_090;
    public $_091;
    public $_092;
    public $_093;
    public $_094;
    public $_095;
    public $_100;

    public $_110;
    public $_120;
    public $_130;
    public $_140;

    public function __construct(Issuer $issuer, ?int $year, $config = [])
    {
        $this->year = $year ?: (new \DateTimeImmutable('last year'))->format('Y');
        $this->issuerId = $issuer->id;

        $report = $year === null ? null : CashFlowReport::getOneByCriteria([
            'issuer_id' => $issuer->id,
            '_year' => $this->year,
        ]);

        if ($report !== null) {
            $this->_020 = $report->_020;
            $this->_021 = $report->_021;
            $this->_022 = $report->_022;
            $this->_023 = $report->_023;
            $this->_024 = $report->_024;

            $this->_030 = $report->_030;
            $this->_031 = $report->_031;
            $this->_032 = $report->_032;
            $this->_033 = $report->_033;
            $this->_040 = $report->_040;

            $this->_050 = $report->_050;
            $this->_051 = $report->_051;
            $this->_052 = $report->_052;
            $this->_053 = $report->_053;
            $this->_054 = $report->_054;
            $this->_055 = $report->_055;

            $this->_060 = $report->_060;
            $this->_061 = $report->_061;
            $this->_062 = $report->_062;
            $this->_063 = $report->_063;
            $this->_064 = $report->_064;
            $this->_070 = $report->_070;

            $this->_080 = $report->_080;
            $this->_081 = $report->_081;
            $this->_082 = $report->_082;
            $this->_083 = $report->_083;
            $this->_084 = $report->_084;

            $this->_090 = $report->_090;
            $this->_091 = $report->_091;
            $this->_092 = $report->_092;
            $this->_093 = $report->_093;
            $this->_094 = $report->_094;
            $this->_095 = $report->_095;
            $this->_100 = $report->_100;

            $this->_110 = $report->_110;
            $this->_120 = $report->_120;
            $this->_130 = $report->_130;
            $this->_140 = $report->_140;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        $nullable = [
            '_021',
            '_022',
            '_023',
            '_024',
            '_031',
            '_032',
            '_033',

            '_050',
            '_051',
            '_052',
            '_053',
            '_054',
            '_055',

            '_060',
            '_061',
            '_062',
            '_063',
            '_064',
            '_070',

            '_081',
            '_082',
            '_083',
            '_084',

            '_091',
            '_092',
            '_093',
            '_094',
            '_095',

            '_120',
            '_130',
            '_140',
        ];

        $required = [
            '_020',
            '_030',
            '_040',
            '_080',
            '_090',
            '_100',
            '_110',
        ];

        return [
            [['issuerId'], 'required'],
            [['year'], 'required'],
            [['year'], 'date', 'format' => 'php:Y'],

            [$nullable, 'default', 'value' => null],
            [array_merge($nullable, $required), 'double'],
            [$required, 'required', 'message' => 'Заполните.'],
        ];
    }
}