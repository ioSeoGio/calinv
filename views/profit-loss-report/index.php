<?php

/** @var yii\web\View $this */
/** @var Issuer $model */
/** @var ActiveDataProvider $dataProvider */
/** @var FinancialReportByApiCreateForm $apiCreateForm */

use app\widgets\ShowCopyNumberColumn;
use src\Action\Issuer\FinancialReport\FinancialReportByApiCreateForm;
use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReport;
use src\Entity\Issuer\Issuer;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

$this->params['breadcrumbs.homeLink'] = false;
$this->params['breadcrumbs'][] = ['label' => 'Эмитенты', 'url' => ['issuer/index']];
$this->params['breadcrumbs'][] = $model->name;
$this->title = 'Отчет о прибылях и убытках ' . $model->name;
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
    'url' => '/profit-loss-report/fetch-external'
]) ?>
<div class="issuer-view">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            '_year',
            [
                'attribute' => '_termType',
                'value' => function (ProfitLossReport $model) {
                    return $model->termType->value;
                }
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => '_210',
                'format' => 'decimal',
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => '_240',
                'format' => 'decimal',
            ],
        ],
    ]) ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
    });
');
