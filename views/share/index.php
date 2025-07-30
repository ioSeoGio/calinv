<?php

use lib\FrontendHelper\DetailViewCopyHelper;
use lib\FrontendHelper\GoodBadValueViewHelper;
use lib\FrontendHelper\SimpleNumberFormatter;
use src\Action\Share\ShareCreateForm;
use src\Action\Share\ShareSearchForm;
use src\Entity\Issuer\Issuer;
use src\Entity\Share\Share;
use src\Integration\Bcse\BcseUrlHelper;
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
            'format' => 'raw',
            'value' => function (Share $model) {
                return Html::a($model->issuer->name, ['/issuer/view', 'id' => $model->issuer->id]);
            },
            'filter' => Html::activeDropDownList(
                $shareSearchForm,
                'issuerId',
                ['All' => 'Все'] + ArrayHelper::map(Issuer::find()->all(), 'id', 'name'),
                ['class' => 'form-control']
            ),
        ],
        [
            'label' => 'Выпуск',
            'attribute' => 'formattedName',
            'format' => 'raw',
            'value' => function (Share $model) {
                return DetailViewCopyHelper::render($model, 'formattedName');
            },
        ],
        [
            'attribute' => 'registerNumber',
            'format' => 'raw',
            'value' => function (Share $model) {
                return DetailViewCopyHelper::render($model, 'registerNumber');
            },
        ],
        [
            'attribute' => 'lastDealDate',
            'format' => 'raw',
            'value' => function (Share $model) {
                return $model->lastDealDate
                    ? Html::a(Yii::$app->formatter->asDatetime($model->lastDealDate), BcseUrlHelper::getShareUrl($model), ['target' => '_blank'])
                    : null;
            }
        ],
        [
            'attribute' => 'lastDealChangePercent',
            'format' => 'raw',
            'value' => function (Share $model) {
                return $model->lastDealChangePercent !== null
                    ? GoodBadValueViewHelper::execute($model->lastDealChangePercent, 0, postfix: '%')
                    : 'Не задано';
            }
        ],
        [
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
            'attribute' => 'denomination',
            'value' => function (Share $model) {
                return SimpleNumberFormatter::toView($model->denomination);
//                    . ' '
//                    . ($model->issueDate <= new DateTimeImmutable(2016) ? 'BYR (до деноминации)' : 'BYN');
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