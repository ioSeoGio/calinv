<?php

use app\models\FinancialInstrument\Bond;
use app\models\IssuerRating\IssuerRating;
use yii\base\Model;
use yii\bootstrap5\Html;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var ArrayDataProvider $bondsDataProvider */
/** @var Model $bondsSearchForm */
/** @var Model $bondForm */

?>
<?= $this->render('tabs', []); ?>
<?= $this->render('bond_creation', [
    'bondForm' => $bondForm,
]); ?>
<?= GridView::widget([
    'dataProvider' => $bondsDataProvider,
    'filterModel' => $bondsSearchForm,
    'columns' => [
        [
            'label' => 'имя выпуска',
            'attribute' => 'name',
        ],
        [
            'label' => 'эмитент',
            'attribute' => 'issuerRating.issuer',
            'filter' => Html::activeDropDownList(
                $bondsSearchForm,
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
            'value' => function (Bond $model) {
                return $model->denomination . ' р.';
            }
        ],
        [
            'label' => 'ставка',
            'attribute' => 'rate',
            'value' => function (Bond $model) {
                return $model->rate . ' %';
            }
        ],
        [
            'label' => 'прибыль к погашению, %',
            'value' => function (Bond $model) {
                $maturityDate = new DateTimeImmutable($model->maturityDate);
                $timeToMaturity = $maturityDate->diff(new DateTimeImmutable());

                return round($model->rate * $timeToMaturity->days / 365, 1) . ' %';
            }
        ],
        [
            'label' => 'текущая цена',
            'attribute' => 'currentPrice',
            'value' => function (Bond $model) {
                return $model->currentPrice . ' р.';
            }
        ],
        [
            'label' => 'погашение',
            'value' => function (Bond $model) {
                return \Yii::$app->formatter->asRelativeTime((new DateTime($model->maturityDate))->diff(new DateTime()));
            }
        ],
        [
            'label' => 'выпуск',
            'attribute' => 'issueDate',
        ],
        [
            'label' => 'погашение',
            'attribute' => 'maturityDate',
        ],
        [
            'label' => 'объем выпуска',
            'attribute' => 'volumeIssued',
            'value' => function (Bond $model) {
                return $model->volumeIssued . ' шт.';
            }
        ],
    ],
]) ?>
