<?php

namespace src\Action\Issuer\FinancialReport;

use yii\base\Model;

class FinancialReportByApiCreateForm extends Model
{
    public $year;

    public function __construct($config = [])
    {
        $this->year = (new \DateTimeImmutable('last year'))->format('Y');

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