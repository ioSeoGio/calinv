<?php

/** @var yii\web\View $this */
/** @var Issuer $model */
/** @var ActiveDataProvider $dataProvider */
/** @var FinancialReportByApiCreateForm $apiCreateForm */

use app\widgets\ShowCopyNumberColumn;
use src\Action\Issuer\FinancialReport\FinancialReportByApiCreateForm;
use src\Entity\Issuer\FinanceReport\CashFlowReport\CashFlowReport;
use src\Entity\Issuer\Issuer;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

$this->params['breadcrumbs.homeLink'] = false;
$this->params['breadcrumbs'][] = ['label' => 'Эмитенты', 'url' => ['issuer/index']];
$this->params['breadcrumbs'][] = $model->name;
$this->title = 'Отчет о движении денежных средств (ДДС) ' . $model->name;
?>

<?= $this->render('@views/_parts/issuer_tabs', [
    'model' => $model,
]); ?>
<?php //= $this->render('create', [
//    'accountingBalanceCreateForm' => $accountingBalanceCreateForm,
//    'issuer' => $model,
//]) ?>
<?= $this->render('@views/_parts/create_by_api', [
    'createForm' => $apiCreateForm,
    'issuer' => $model,
    'url' => '/cash-flow-report/fetch-external'
]) ?>
<div class="issuer-view">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'year',
            [
                'attribute' => '_termType',
                'value' => function (CashFlowReport $model) {
                    return $model->termType->value;
                }
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => '_040',
                'format' => 'decimal',
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => '_070',
                'format' => 'decimal',
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => '_100',
                'format' => 'decimal',
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => '_110',
                'format' => 'decimal',
            ],
        ],
    ]) ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
    });
');
