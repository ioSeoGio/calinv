<?php

namespace src\Action\Issuer\FinancialReport\ProfitLossReport;

use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReport;
use src\Entity\Issuer\Issuer;
use yii\base\Model;

class ProfitLossReportCreateForm extends Model
{
    public int $issuerId;
    public $year;

    public $_010;
    public $_020;
    public $_030;
    public $_040;
    public $_050;
    public $_060;
    public $_070;
    public $_080;
    public $_090;

    public $_100;
    public $_101;
    public $_102;
    public $_103;
    public $_104;

    public $_110;
    public $_111;
    public $_112;

    public $_120;
    public $_121;
    public $_122;

    public $_130;
    public $_131;
    public $_132;
    public $_133;

    public $_140;
    public $_150;
    public $_160;
    public $_170;
    public $_180;
    public $_190;

    public $_210;
    public $_220;
    public $_230;
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
            $this->_020 = $report->_020;
            $this->_030 = $report->_030;
            $this->_040 = $report->_040;
            $this->_050 = $report->_050;
            $this->_060 = $report->_060;
            $this->_070 = $report->_070;
            $this->_080 = $report->_080;
            $this->_090 = $report->_090;

            $this->_100 = $report->_100;
            $this->_101 = $report->_101;
            $this->_102 = $report->_102;
            $this->_103 = $report->_103;
            $this->_104 = $report->_104;

            $this->_110 = $report->_110;
            $this->_111 = $report->_111;
            $this->_112 = $report->_112;

            $this->_120 = $report->_120;
            $this->_121 = $report->_121;
            $this->_122 = $report->_122;

            $this->_130 = $report->_130;
            $this->_131 = $report->_131;
            $this->_132 = $report->_132;
            $this->_133 = $report->_133;

            $this->_140 = $report->_140;
            $this->_150 = $report->_150;
            $this->_160 = $report->_160;
            $this->_170 = $report->_170;
            $this->_180 = $report->_180;
            $this->_190 = $report->_190;

            $this->_210 = $report->_210;
            $this->_220 = $report->_220;
            $this->_230 = $report->_230;
            $this->_240 = $report->_240;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        $nullable = [
            '_020',
            '_030',
            '_040',
            '_050',
            '_060',
            '_070',
            '_080',

            '_100',
            '_101',
            '_102',
            '_103',
            '_104',

            '_110',
            '_111',
            '_112',

            '_120',
            '_121',
            '_122',

            '_130',
            '_131',
            '_132',
            '_133',

            '_140',
            '_150',
            '_160',
            '_170',
            '_180',
            '_190',

            '_220',
            '_230',
        ];

        $required = [
            '_010',
            '_090',
            '_210',
            '_240',
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