<?php

/** @var yii\web\View $this */
/** @var Issuer $model */
/** @var ActiveDataProvider $dataProvider */
/** @var AccountingBalanceCreateForm $createForm */
/** @var FinancialReportByApiCreateForm $apiCreateForm */

use app\widgets\ShowCopyNumberColumn;
use src\Action\Issuer\FinancialReport\AccountingBalance\AccountingBalanceCreateForm;
use src\Action\Issuer\FinancialReport\FinancialReportByApiCreateForm;
use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;
use src\Entity\Issuer\Issuer;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

$this->params['breadcrumbs.homeLink'] = false;
$this->params['breadcrumbs'][] = ['label' => 'Эмитенты', 'url' => ['issuer/index']];
$this->params['breadcrumbs'][] = $model->name;
$this->title = 'Бухгалтерский баланс ' . $model->name;
?>

<?= $this->render('@views/_parts/issuer_tabs', [
    'model' => $model,
]); ?>
<?= $this->render('create', [
    'accountingBalanceCreateForm' => $createForm,
    'issuer' => $model,
]) ?>
<?= $this->render('@views/_parts/create_by_api', [
    'createForm' => $apiCreateForm,
    'issuer' => $model,
    'url' => '/accounting-balance/fetch-external'
]) ?>
<div class="issuer-view">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            '_year',
            [
                'attribute' => '_termType',
                'value' => function (AccountingBalance $model) {
                    return $model->termType->value;
                }
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => '_190',
                'format' => 'decimal',
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => '_290',
                'format' => 'decimal',
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => '_590',
                'format' => 'decimal',
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => '_690',
                'format' => 'decimal',
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => '_490',
                'format' => 'decimal',
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => '_700',
                'format' => 'decimal',
            ],
        ],
    ]) ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
    });
');
