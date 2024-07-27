<?php

use app\models\FinancialInstrument\Share;
use app\models\IssuerRating\IssuerRating;
use yii\base\Model;
use yii\bootstrap5\Html;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var ArrayDataProvider $sharesDataProvider */
/** @var Model $sharesSearchForm */
/** @var Model $shareForm */

?>
<?= $this->render('tabs', []); ?>
<?= $this->render('share_creation', [
        'shareForm' => $shareForm,
]); ?>
<?= $sharesContent = GridView::widget([
    'dataProvider' => $sharesDataProvider,
    'filterModel' => $sharesSearchForm,
    'columns' => [
        [
            'label' => 'имя выпуска',
            'attribute' => 'name',
        ],
        [
            'label' => 'эмитент',
            'attribute' => 'issuerRating.issuer',
            'filter' => Html::activeDropDownList(
                $sharesSearchForm,
                'issuerId',
                array_merge(
                    ['All' => 'Все'],
                    ArrayHelper::map(
                        IssuerRating::find()->all(),
                        fn (IssuerRating $issuerRating) => (string) $issuerRating->_id,
                        'issuer'
                ),
            ), ['class' => 'form-control']),
        ],
        [
            'label' => 'номинал',
            'attribute' => 'denomination',
            'value' => function (Share $model) {
                return $model->denomination . ' р.';
            }
        ],
        [
            'label' => 'текущая цена',
            'attribute' => 'currentPrice',
            'value' => function (Share $model) {
                return $model->currentPrice . ' р.';
            }
        ],
        [
            'label' => 'объем выпуска',
            'attribute' => 'volumeIssued',
            'value' => function (Share $model) {
                return $model->volumeIssued . ' шт.';
            }
        ],
    ],
]) ?>
