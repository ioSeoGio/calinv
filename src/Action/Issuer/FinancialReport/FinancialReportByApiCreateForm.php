<?php

namespace src\Action\Issuer\FinancialReport;

use yii\base\Model;

class FinancialReportByApiCreateForm extends Model
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