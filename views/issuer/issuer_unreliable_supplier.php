<?php

/** @var yii\web\View $this */
/** @var ActiveDataProvider $dataProvider */
/** @var UnreliableSupplierSearchForm $searchForm */

use src\Action\Issuer\UnreliableSupplier\UnreliableSupplierSearchForm;
use src\Entity\Issuer\UnreliableSupplier\UnreliableSupplier;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Недобросовестные поставщики';
?>
<?= $this->render('tabs', []); ?>
<div>
    Время последнего обновления: <?= Yii::$app->formatter->asDatetime(UnreliableSupplier::getLastUpdateSessionDate()) ?>
    <div>
        <?= Html::a('Обновить', ['renew-unreliable-supplier'], ['class' => 'btn btn-success']) ?>
    </div>
</div>
<div class="unreliable-supplier-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchForm,
        'columns' => [
            '_pid',
            'issuerName',
            'reason',
            [
                'attribute' => '_addDate',
                'value' => function (UnreliableSupplier $model) {
                    return Yii::$app->formatter->asDatetime($model->_addDate / 1000);
                },
            ],
        ],
    ]) ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
    });
');
