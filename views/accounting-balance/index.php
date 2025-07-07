<?php

/** @var yii\web\View $this */
/** @var Issuer $model */
/** @var ActiveDataProvider $dataProvider */
/** @var AccountingBalanceCreateForm $accountingBalanceCreateForm */

use app\widgets\ShowCopyNumberColumn;
use src\Action\Issuer\AccountingBalance\AccountingBalanceCreateForm;
use src\Entity\Issuer\AccountingBalance\AccountingBalance;
use src\Entity\Issuer\Issuer;
use supplyhog\ClipboardJs\ClipboardJsWidget;
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
                'attribute' => 'longAsset',
                'format' => 'decimal',
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => 'shortAsset',
                'format' => 'decimal',
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => 'longDebt',
                'format' => 'decimal',
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => 'shortDebt',
                'format' => 'decimal',
            ],
            [
                'class' => ShowCopyNumberColumn::class,
                'attribute' => 'capital',
                'format' => 'decimal',
            ],
        ],
    ]) ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
    });
');
