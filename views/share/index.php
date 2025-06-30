<?php

use src\Action\Share\ShareCreateForm;
use src\Action\Share\ShareSearchForm;
use src\Entity\Issuer\Issuer;
use src\Entity\Share\Share;
use src\Helper\GoodBadValueViewHelper;
use src\Helper\SimpleNumberFormatter;
use yii\bootstrap5\Html;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var ArrayDataProvider $shareDataProvider */
/** @var ShareSearchForm $shareSearchForm */
/** @var ShareCreateForm $shareCreateForm */

?>
<?= $this->render('../_parts/_tabs', []); ?>
<?= $sharesContent = GridView::widget([
    'dataProvider' => $shareDataProvider,
    'filterModel' => $shareSearchForm,
    'columns' => [
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
        'registerNumber',
        [
            'attribute' => 'lastDealDate',
            'format' => 'date',
        ],
        [
            'label' => 'изменение по последней сделке',
            'attribute' => 'lastDealChangePercent',
            'format' => 'raw',
            'value' => function (Share $model) {
                return $model->lastDealChangePercent
                    ? GoodBadValueViewHelper::execute($model->lastDealChangePercent, 0, postfix: '%')
                    : 'Не задано';
            }
        ],
        [
            'label' => 'текущая цена',
            'attribute' => 'currentPrice',
            'format' => 'html',
            'value' => function (Share $model) {
                return Html::tag(
                    name: 'span',
                    content: $model->currentPrice ? SimpleNumberFormatter::toView($model->currentPrice) . ' р.' : 'Не задано',
                    options: ['class' => 'text-primary']
                );
            }
        ],
        [
            'label' => 'номинал',
            'attribute' => 'denomination',
            'value' => function (Share $model) {
                return SimpleNumberFormatter::toView($model->denomination) . ' р.';
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
            'attribute' => 'totalIssuedAmount',
            'value' => function (Share $model) {
                return SimpleNumberFormatter::toView($model->totalIssuedAmount, 0) . ' шт.';
            }
        ],
        [
            'attribute' => 'issueDate',
            'format' => 'date',
        ],
        [
            'attribute' => 'closingDate',
            'format' => 'date',
        ],
    ],
]) ?>