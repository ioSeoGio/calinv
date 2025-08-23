<?php

namespace src\Action\Issuer\FinancialReport\AccountingBalance;

use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;
use src\Entity\Issuer\Issuer;
use yii\base\Model;

class AccountingBalanceCreateForm extends Model
{
    public int $issuerId;
    public $year;

    public $_110;
    public $_120;
    public $_130;
    public $_131;
    public $_140;
    public $_150;
    public $_160;
    public $_170;
    public $_180;
    public $_190;

    public $_210;
    public $_211;
    public $_213;
    public $_214;
    public $_215;
    public $_230;
    public $_240;
    public $_250;
    public $_260;
    public $_270;
    public $_280;
    public $_290;

    public $_410;
    public $_440;
    public $_450;
    public $_460;
    public $_470;
    public $_480;
    public $_490;

    public $_510;
    public $_540;
    public $_590;

    public $_610;
    public $_620;
    public $_630;
    public $_631;
    public $_632;
    public $_633;
    public $_634;
    public $_635;
    public $_636;
    public $_637;
    public $_638;
    public $_650;
    public $_670;
    public $_690;

    public $_700;

    public function __construct(Issuer $issuer, ?int $year, $config = [])
    {
        $this->year = $year ?: (new \DateTimeImmutable('last year'))->format('Y');
        $this->issuerId = $issuer->id;

        $report = $year === null ? null : AccountingBalance::getOneByCriteria([
            'issuer_id' => $issuer->id,
            '_year' => $this->year,
        ]);

        if ($report !== null) {
            $this->_110 = $report->_110;
            $this->_120 = $report->_120;
            $this->_130 = $report->_130;
            $this->_131 = $report->_131;
            $this->_140 = $report->_140;
            $this->_150 = $report->_150;
            $this->_160 = $report->_160;
            $this->_170 = $report->_170;
            $this->_180 = $report->_180;
            $this->_190 = $report->_190;

            $this->_210 = $report->_210;
            $this->_211 = $report->_211;
            $this->_213 = $report->_213;
            $this->_214 = $report->_214;
            $this->_215 = $report->_215;
            $this->_230 = $report->_230;
            $this->_240 = $report->_240;
            $this->_250 = $report->_250;
            $this->_260 = $report->_260;
            $this->_270 = $report->_270;
            $this->_280 = $report->_280;
            $this->_290 = $report->_290;

            $this->_410 = $report->_410;
            $this->_440 = $report->_440;
            $this->_450 = $report->_450;
            $this->_460 = $report->_460;
            $this->_470 = $report->_470;
            $this->_480 = $report->_480;
            $this->_490 = $report->_490;

            $this->_510 = $report->_510;
            $this->_540 = $report->_540;
            $this->_590 = $report->_590;

            $this->_610 = $report->_610;
            $this->_620 = $report->_620;
            $this->_630 = $report->_630;
            $this->_631 = $report->_631;
            $this->_632 = $report->_632;
            $this->_633 = $report->_633;
            $this->_634 = $report->_634;
            $this->_635 = $report->_635;
            $this->_636 = $report->_636;
            $this->_637 = $report->_637;
            $this->_638 = $report->_638;
            $this->_650 = $report->_650;
            $this->_670 = $report->_670;
            $this->_690 = $report->_690;

            $this->_700 = $report->_700;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        $nullable = [
            '_110',
            '_120',
            '_130',
            '_131',
            '_140',
            '_150',
            '_160',
            '_170',
            '_180',

            '_210',
            '_211',
            '_213',
            '_214',
            '_215',
            '_230',
            '_240',
            '_250',
            '_260',
            '_270',
            '_280',

            '_410',
            '_440',
            '_450',
            '_460',
            '_470',
            '_480',

            '_510',
            '_540',

            '_610',
            '_620',
            '_630',
            '_631',
            '_632',
            '_633',
            '_634',
            '_635',
            '_636',
            '_637',
            '_638',
            '_650',
            '_670',
        ];
        $required = [
            '_190',
            '_290',
            '_490',
            '_590',
            '_690',
            '_700',
        ];

        return [
            [['issuerId'], 'required'],
            [['year'], 'required'],
            [['year'], 'date', 'format' => 'php:Y'],

            [['_690'], 'compare', 'compareValue' => 0, 'operator' => '!=', 'type' => 'number'],
            [$nullable, 'default', 'value' => null],
            [array_merge($nullable, $required), 'double'],
            [$required, 'required', 'message' => 'Заполните.'],
        ];
    }
}