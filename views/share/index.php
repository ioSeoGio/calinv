<?php

use lib\FrontendHelper\DetailViewCopyHelper;
use lib\FrontendHelper\GoodBadValueViewHelper;
use lib\FrontendHelper\NullableValue;
use lib\FrontendHelper\SimpleNumberFormatter;
use src\Action\Share\ShareCreateForm;
use src\Action\Share\ShareSearchForm;
use src\Entity\Issuer\Issuer;
use src\Entity\Share\Share;
use src\Integration\Bcse\BcseUrlHelper;
use src\ViewHelper\Tools\Badge;
use yii\bootstrap5\Html;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/** @var ArrayDataProvider $shareDataProvider */
/** @var ShareSearchForm $shareSearchForm */
/** @var ShareCreateForm $shareCreateForm */
/** @var bool $showClosingDate */

?>
<?= $this->render('../_parts/_tabs', []); ?>

<?php $columns = [
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
                ? Html::a(Yii::$app->formatter->asDate($model->lastDealDate), BcseUrlHelper::getShareUrl($model), ['target' => '_blank'])
                : null;
        }
    ],
    [
        'attribute' => 'lastDealChangePercent',
        'format' => 'raw',
        'value' => function (Share $model) {
            return $model->lastDealChangePercent !== null
                ? GoodBadValueViewHelper::asBadge($model->lastDealChangePercent, 0.00, true, '%')
                : NullableValue::printNull();
        }
    ],
    [
        'attribute' => 'currentPrice',
        'format' => 'html',
        'value' => function (Share $model) {
            $currentPrice = $model->currentPrice
                ? Badge::neutral($model->currentPrice . ' р.')
                : NullableValue::printNull();

            $minPrice = $model->minPrice
                ? Badge::neutral('мин ' . $model->minPrice . ' р.')
                : '';

            $maxPrice = $model->maxPrice
                ? Badge::neutral('макс ' . $model->maxPrice . ' р.')
                : '';

            if ($minPrice && $minPrice) {
                return $currentPrice . "<br>" . "$minPrice - $maxPrice";
            }

            return $currentPrice;
        },
        'contentOptions' => ['class' => 'text-left', 'style' => 'min-width: 250px'],
    ],
    [
        'attribute' => 'denomination',
        'format' => 'html',
        'value' => function (Share $model) {
            return $model->denomination
                ? Badge::neutral($model->denomination . ' р.')
                : NullableValue::printNull();
        }
    ],
    [
        'label' => 'Подробнее',
        'format' => 'html',
        'value' => function (Share $model) {
            $shareDealsInfoExists = $model->getShareDeals()->count();

            $chart = $shareDealsInfoExists
                ? Html::a('График', Url::to(['/share/deal-info', 'id' => $model->id]), ['class' => 'btn btn-primary btn-sm border-bottom'])
                : '';

            $fairPrice = $shareDealsInfoExists
                ? Html::a('Расчет цены', Url::to(['/share/fair-price', 'id' => $model->id]), ['class' => 'btn btn-primary btn-sm'])
                : '';

            return $chart . $fairPrice;
        },
        'options' => ['style' => 'min-width: 120px'],
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
]; ?>

<?php if ($showClosingDate): ?>
    <?php $columns = array_merge($columns, [
        [
            'attribute' => 'closingDate',
            'format' => 'date',
        ],
    ]); ?>
<?php endif ?>

<?= $sharesContent = GridView::widget([
    'dataProvider' => $shareDataProvider,
    'pager' => [
        'class' => \yii\bootstrap5\LinkPager::class,
    ],
    'filterModel' => $shareSearchForm,
    'columns' => $columns,
]) ?>