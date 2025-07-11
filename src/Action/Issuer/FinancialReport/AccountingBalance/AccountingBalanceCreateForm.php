<?php

namespace src\Action\Issuer\FinancialReport\AccountingBalance;

use src\Entity\Issuer\FinanceTermType;
use src\Entity\Issuer\Issuer;
use yii\base\Model;

class AccountingBalanceCreateForm extends Model
{
    public int $issuerId;
    public $termType;
    public $year;

    public $longAsset = 600;
    public $shortAsset = 5;
    public $longDebt = 5;
    public $shortDebt = 5;
    public $capital = 5;

    public function __construct(Issuer $issuer, $config = [])
    {
        $this->year = date('Y');
        $this->issuerId = $issuer->id;

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['year'], 'date', 'format' => 'php:Y'],
            [['termType'], 'in', 'range' => FinanceTermType::toValidateRange()],
            [['shortAsset', 'longAsset', 'shortDebt', 'longDebt', 'capital'], 'double'],

            [[
                'issuerId',
                'termType',
                'year',
                'shortAsset',
                'longAsset',
                'shortDebt',
                'longDebt',
                'capital',
            ], 'required', 'message' => 'Заполните.'],
        ];
    }
}