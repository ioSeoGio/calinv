<?php

use src\Action\Share\ShareCreateForm;
use src\Action\Share\ShareSearchForm;
use src\Entity\Issuer\Issuer;
use src\Entity\Share\Share;
use src\Helper\SimpleNumberFormatter;
use src\IssuerRatingCalculator\Static\FairSharePriceCalculator;
use yii\bootstrap5\Html;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var ArrayDataProvider $shareDataProvider */
/** @var ShareSearchForm $shareSearchForm */
/** @var ShareCreateForm $shareCreateForm */

?>
<?= $this->render('../_parts/_tabs', []); ?>
<?= $this->render('create', [
    'shareCreateForm' => $shareCreateForm,
]); ?>
<?= $sharesContent = GridView::widget([
    'dataProvider' => $shareDataProvider,
    'filterModel' => $shareSearchForm,
    'columns' => [
        [
            'label' => 'имя выпуска',
            'attribute' => 'name',
        ],
        [
            'label' => 'эмитент',
            'attribute' => 'issuer.name',
            'filter' => Html::activeDropDownList(
                $shareSearchForm,
                'issuerId',
                array_merge(
                    ['All' => 'Все'],
                    ArrayHelper::map(Issuer::find()->all(), fn (Issuer $issuer) => $issuer->id, 'name'),
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
//        [
//            'label' => 'справедливая цена к ликвидации',
//            'format' => 'raw',
//            'value' => function (Share $model) {
//                $r = '';
//                foreach (FairSharePriceCalculator::calculateForLiquidation($model) as $fairPrice) {
//                    $r .= Html::tag(
//                        name: 'span',
//                        content: SimpleNumberFormatter::toView($fairPrice) . ' р.',
//                        options: ['class' => $fairPrice >= $model->currentPrice ? 'text-success' : 'text-danger']
//                    ) . '<br>';
//                }
//
//                return $r;
//            }
//        ],
//        [
//            'label' => 'справед. цена по доходу',
//            'format' => 'raw',
//            'value' => function (Share $model) {
//                $values = '';
//                foreach (FairSharePriceCalculator::calculateForEarning($model) as $fairPrice) {
//                    $values .= Html::tag(
//                        name: 'span',
//                        content: SimpleNumberFormatter::toView($fairPrice) . ' р.',
//                        options: ['class' => $fairPrice >= $model->currentPrice ? 'text-success' : 'text-danger']
//                    ) . '<br>';
//                }
//
//                return $values;
//            }
//        ],
//        [
//            'label' => 'справед. цена по доходу (к номиналу акции)',
//            'format' => 'raw',
//            'value' => function (Share $model) {
//                $values = '';
//                foreach (FairSharePriceCalculator::calculateForEarning($model) as $fairPrice) {
//                    $values .= Html::tag(
//                        name: 'span',
//                        content: SimpleNumberFormatter::toView($fairPrice * $model->denomination) . ' р.',
//                        options: ['class' => $fairPrice >= $model->currentPrice ? 'text-success' : 'text-danger']
//                    ) . '<br>';
//                }
//
//                return $values;
//            }
//        ],
        [
            'label' => 'объем выпуска',
            'attribute' => 'volumeIssued',
            'value' => function (Share $model) {
                return SimpleNumberFormatter::toView($model->volumeIssued, 0) . ' шт.';
            }
        ],
    ],
]) ?>