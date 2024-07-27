<?php

namespace app\controllers\FinancialInstrument\Form;

use DateTime;
use yii\base\Model;

class BondForm extends Model
{
    public string $name = 'а21';
    public string $issuer_id = '0';
    public float $denomination = 1;
    public float $rate = 1;
    public float $currentPrice = 1;
    public float $volumeIssued = 1;
    public string $maturityDate;
    public string $issueDate;

    public function init(): void
    {
        $this->issueDate = date('d.m.Y');
        $this->maturityDate = (new DateTime())->modify('+1 year')->format('d.m.Y');
        parent::init();
    }

    public function rules(): array
    {
        return [
            [[
                'name',
                'issuer_id',
                'denomination',
                'rate',
                'currentPrice',
                'volumeIssued',
                'maturityDate',
                'issueDate',
            ], 'required', 'message' => 'Заполните.'],
            ['maturityDate', 'datetime', 'format' => 'php:d.m.Y'],
            ['issueDate', 'datetime', 'format' => 'php:d.m.Y'],
        ];
    }
}
