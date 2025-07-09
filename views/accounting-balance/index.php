<?php

/** @var yii\web\View $this */
/** @var Issuer $model */
/** @var ActiveDataProvider $dataProvider */
/** @var AccountingBalanceCreateForm $accountingBalanceCreateForm */
/** @var AccountingBalanceApiCreateForm $accountingBalanceApiCreateForm */

use app\widgets\ShowCopyNumberColumn;
use src\Action\Issuer\AccountingBalance\AccountingBalanceApiCreateForm;
use src\Action\Issuer\AccountingBalance\AccountingBalanceCreateForm;
use src\Entity\Issuer\AccountingBalance\AccountingBalance;
use src\Entity\Issuer\Issuer;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

$this->params['breadcrumbs.homeLink'] = false;
$this->params['breadcrumbs'][] = ['label' => 'Эмитенты', 'url' => ['issuer/index']];
$this->params['breadcrumbs'][] = $model->name;
$this->title = 'Бухгалтерский баланс ' . $model->name;
?>

<?= $this->render('@views/issuer/issuer_tabs', [
    'model' => $model,
]); ?>
<?= $this->render('create', [
    'accountingBalanceCreateForm' => $accountingBalanceCreateForm,
    'issuer' => $model,
]) ?>
<?= $this->render('create_by_api', [
    'createForm' => $accountingBalanceApiCreateForm,
    'issuer' => $model,
]) ?>
<div class="issuer-view">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'year',
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
        ],
    ]) ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
    });
');
