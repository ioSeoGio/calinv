<?php

namespace app\widgets;

use src\Entity\Issuer\FinanceReport\FinancialReportInterface;
use yii\base\Model;
use yii\base\Widget;

class ReversedFinancialReportTableWidget extends Widget
{
    /** @var FinancialReportInterface[] $models */
    public array $models;
    public string $saveAction;
    public string $validateAction;
    public Model $createForm;

    public function run()
    {
        return $this->render('reversed-financial-report-table', [
            'models' => $this->models,
            'saveAction' => $this->saveAction,
            'validateAction' => $this->validateAction,
            'createForm' => $this->createForm,
        ]);
    }
}
