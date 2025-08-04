<?php

namespace app\widgets;

use src\Entity\Issuer\FinanceReport\FinancialReportInterface;
use yii\base\Widget;

class ReversedFinancialReportTableWidget extends Widget
{
    /** @var FinancialReportInterface[] $models */
    public array $models;

    public function run()
    {
        return $this->render('reversed-financial-report-table', [
            'models' => $this->models,
        ]);
    }
}
