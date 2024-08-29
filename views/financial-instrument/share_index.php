<?php

use app\models\FinancialInstrument\Share;
use app\models\IssuerRating\IssuerRating;
use src\Helper\SimpleNumberFormatter;
use src\IssuerRatingCalculator\Static\FairSharePriceCalculator;
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
                return SimpleNumberFormatter::toView($model->denomination) . ' р.';
            }
        ],
        [
            'label' => 'текущая цена',
            'attribute' => 'currentPrice',
            'format' => 'raw',
            'value' => function (Share $model) {
                return Html::tag(
                    name: 'span',
                    content: SimpleNumberFormatter::toView($model->currentPrice) . ' р.',
                    options: ['class' => 'text-primary']
                );
            }
        ],
        [
            'label' => 'справедливая цена к ликвидации',
            'format' => 'raw',
            'value' => function (Share $model) {
                $r = '';
                foreach (FairSharePriceCalculator::calculateForLiquidation($model) as $fairPrice) {
                    $r .= Html::tag(
                        name: 'span',
                        content: SimpleNumberFormatter::toView($fairPrice) . ' р.',
                        options: ['class' => $fairPrice >= $model->currentPrice ? 'text-success' : 'text-danger']
                    ) . '<br>';
                }

                return $r;
            }
        ],
        [
            'label' => 'справед. цена по доходу',
            'format' => 'raw',
            'value' => function (Share $model) {
                $values = '';
                foreach (FairSharePriceCalculator::calculateForEarning($model) as $fairPrice) {
                    $values .= Html::tag(
                        name: 'span',
                        content: SimpleNumberFormatter::toView($fairPrice) . ' р.',
                        options: ['class' => $fairPrice >= $model->currentPrice ? 'text-success' : 'text-danger']
                    ) . '<br>';
                }

                return $values;
            }
        ],
        [
            'label' => 'справед. цена по доходу (к номиналу акции)',
            'format' => 'raw',
            'value' => function (Share $model) {
                $values = '';
                foreach (FairSharePriceCalculator::calculateForEarning($model) as $fairPrice) {
                    $values .= Html::tag(
                        name: 'span',
                        content: SimpleNumberFormatter::toView($fairPrice * $model->denomination) . ' р.',
                        options: ['class' => $fairPrice >= $model->currentPrice ? 'text-success' : 'text-danger']
                    ) . '<br>';
                }

                return $values;
            }
        ],
        [
            'label' => 'объем выпуска',
            'attribute' => 'volumeIssued',
            'value' => function (Share $model) {
                return SimpleNumberFormatter::toView($model->volumeIssued, 0) . ' шт.';
            }
        ],
    ],
]) ?>
