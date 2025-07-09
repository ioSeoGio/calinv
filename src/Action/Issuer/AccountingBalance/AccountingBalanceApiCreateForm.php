<?php

namespace src\Action\Issuer\AccountingBalance;

use src\Entity\Issuer\Issuer;
use yii\base\Model;

class AccountingBalanceApiCreateForm extends Model
{
    public $year;

    public function __construct($config = [])
    {
        $this->year = date('Y');

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['year'], 'date', 'format' => 'php:Y'],
            [['year'], 'required', 'message' => 'Заполните.'],
        ];
    }
}