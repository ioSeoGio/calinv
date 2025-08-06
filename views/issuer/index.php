<?php

/** @var yii\web\View $this */
/** @var ActiveDataProvider $dataProvider */
/** @var IssuerCreateForm $createForm */
/** @var IssuerSearchForm $searchForm */

use app\widgets\GuardedActionColumn;
use lib\FrontendHelper\DetailViewCopyHelper;
use lib\FrontendHelper\GoodBadValueViewHelper;
use lib\FrontendHelper\Icon;
use lib\FrontendHelper\SimpleNumberFormatter;
use src\Action\Issuer\IssuerCreateForm;
use src\Action\Issuer\IssuerSearchForm;
use src\Entity\Issuer\Issuer;
use src\Entity\User\UserRole;
use src\IssuerRatingCalculator\CapitalizationByShareCalculator;
use src\ViewHelper\ExpressRating\ExpressRatingViewHelper;
use src\ViewHelper\IssuerIcon\IssuerStateIconsPrinter;
use yii\bootstrap5\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Калькулятор эмитентов';
?>
<?= $this->render('tabs', []); ?>
<div class="calculator-index">

    <?php if (Yii::$app->user->can(UserRole::admin->value)): ?>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'action' => Url::to(['/issuer/create']),
            'validationUrl' => Url::to(['/issuer/validate']),

            'enableAjaxValidation'      => true,
            'enableClientValidation'    => true,
            'validateOnChange'          => true,
            'validateOnSubmit'          => true,
            'validateOnBlur'            => true,
        ]); ?>
            <table class="table">
                <tr>
                    <th scope="col">
                        УНП
                    </th>
                </tr>
                <tr>
                    <td>
                        <div class="input-group mb-1">
                            <?= $form->field($createForm, 'pid')->textInput(['class' => 'form-control'])->label(false) ?>
                        </div>
                    </td>
                </tr>
                <div class="mx-auto d-flex justify-content-center">
                    <button class="btn btn-primary" type="submit">Рассчитать</button>
                </div>
            </table>
        <?php ActiveForm::end() ?>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'class' => \yii\bootstrap5\LinkPager::class,
        ],
        'filterModel' => $searchForm,
        'columns' => [
            [
                'label' => 'важное',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return IssuerStateIconsPrinter::printMany($model);
                }
            ],
            [
                'label' => 'эмитент',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return Html::a($model->name, ['/issuer/view', 'id' => $model->id]);
                }
            ],
            '_legalStatus',
            [
                'attribute' => '_pid',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return DetailViewCopyHelper::render($model, '_pid');
                }
            ],
            [
                'label' => 'Активные выпуски акций',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return Html::a("Активные акции ({$model->getActiveShares()->count()})", ['/share', 'ShareSearchForm' => [
                            'issuerId' =>  $model->id,
                        ]], ['target' => '_blank', 'class' => 'btn btn-primary'])
                        . '<hr>'
                        . Html::a("Все акции ({$model->getShares()->count()})", ['/share/all-shares', 'ShareSearchForm' => [
                            'issuerId' =>  $model->id,
                        ]], ['target' => '_blank', 'class' => 'btn btn-secondary']);
                }
            ],
            [
                'label' => 'Капитализация',
                'value' => function (Issuer $model) {
                    return SimpleNumberFormatter::toView(CapitalizationByShareCalculator::calculateInGrands($model)) . ' т.р.';
                }
            ],
            [
                'label' => 'k1',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\IssuerCoefficient\K1ViewHelper::render($model);
                },
                'contentOptions' => ['style' => 'min-width: 100px;'],
            ],
            [
                'label' => 'k2',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\IssuerCoefficient\K2ViewHelper::render($model);
                },
                'contentOptions' => ['style' => 'min-width: 100px;'],
            ],
            [
                'label' => 'k3',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\IssuerCoefficient\K3ViewHelper::render($model);
                },
                'contentOptions' => ['style' => 'min-width: 100px;'],
            ],
//            [
//                'label' => 'ЭБ обычный',
//                'format' => 'raw',
//                'value' => function (Issuer $model) {
//                    return ExpressRatingViewHelper::render($model, true);
//                }
//            ],
            [
                'label' => 'BIK рейтинг',
                'value' => function (Issuer $model) {
                    return $model->businessReputationInfo?->rating->value;
                }
            ],
            [
                'header' => 'Экспресс балл ' . Html::a(Icon::printFaq(), Url::to(['/faq#express-rating'])),
                'headerOptions' => ['encode' => false],
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return ExpressRatingViewHelper::render($model, false);
                }
            ],
            [
                'class' => GuardedActionColumn::class,
                'buttonsConfig' => [
                    'view',
                    'coefficient' => [
                        'icon' => 'bi bi-percent',
                        'url' => function (Issuer $model) {
                            return Url::to(['/coefficient/view', 'issuerId' => $model->id]);
                        },
                        'options' => [
                            'title' => 'Коэффициенты',
                        ],
                    ],
                    'accounting-balance' => [
                        'icon' => 'bi bi-file-earmark-text',
                        'url' => function (Issuer $model) {
                            return Url::to(['/accounting-balance/index', 'issuerId' => $model->id]);
                        },
                        'options' => [
                            'title' => 'Бухгалтерский баланс',
                        ],
                    ],
                    'profit-loss-report' => [
                        'icon' => 'bi bi-file-text',
                        'url' => function (Issuer $model) {
                            return Url::to(['/profit-loss-report/index', 'issuerId' => $model->id]);
                        },
                        'options' => [
                            'title' => 'Отчет о прибылях и убытках',
                        ],
                    ],
                    'cash-flow-report' => [
                        'icon' => 'bi bi-file-earmark-break',
                        'url' => function (Issuer $model) {
                            return Url::to(['/cash-flow-report/index', 'issuerId' => $model->id]);
                        },
                        'options' => [
                            'title' => 'Отчет о движении денежных средств',
                        ],
                    ],
                ],
            ],
//            [
//                'attribute' => 'ср. прирост',
//                'format' => 'raw',
//                'value' => function (IssuerRating $model) {
//                    $values = '';
//                    foreach ($model->getAverageGrowth() as $key => $averageValue) {
//                        if (str_contains($key, 'долги')) {
//                            $class = $averageValue > 0 ? 'text-danger' : 'text-success';
//                        } else {
//                            $class = $averageValue > 0 ? 'text-success' : 'text-danger';
//                        }
//
//                        $values .= Html::tag(
//                            name: 'div',
//                            content: "$key: "
//                            . Html::tag(
//                                name: 'span',
//                                content: round($averageValue, 2) . '%',
//                                options: ['class' => $class]
//                            ),
//                            options: ['class' => 'text-primary']
//                        );
//                    }
//                    return $values;
//                }
//            ],
//            [
//                'attribute' => 'мин. прирост',
//                'format' => 'raw',
//                'value' => function (IssuerRating $model) {
//                    $values = '';
//                    foreach ($model->getMinimumGrowth() as $key => $averageValue) {
//                        if (str_contains($key, 'долги')) {
//                            $class = $averageValue > 0 ? 'text-danger' : 'text-success';
//                        } else {
//                            $class = $averageValue > 0 ? 'text-success' : 'text-danger';
//                        }
//
//                        $values .= Html::tag(
//                            name: 'div',
//                            content: "$key: "
//                            . Html::tag(
//                                name: 'span',
//                                content: round($averageValue, 2) . '%',
//                                options: ['class' => $class]
//                            ),
//                            options: ['class' => 'text-primary']
//                        );
//                    }
//                    return $values;
//                }
//            ],
//            [
//                'attribute' => 'k1',
//                'format' => 'raw',
//                'value' => function (IssuerRating $model) {
//                    return CoefficientViewHelper::execute($model, 'k1');
//                }
//            ],
//            [
//                'attribute' => 'k2',
//                'format' => 'raw',
//                'value' => function (IssuerRating $model) {
//                    return CoefficientViewHelper::execute($model, 'k2');
//                }
//            ],
//            [
//                'attribute' => 'k3',
//                'format' => 'raw',
//                'value' => function (IssuerRating $model) {
//                    return CoefficientViewHelper::execute($model, 'k3');
//                }
//            ],
//            [
//                'attribute' => 'k4',
//                'format' => 'raw',
//                'value' => function (IssuerRating $model) {
//                    return CoefficientViewHelper::execute($model, 'k4');
//                }
//            ],
//            [
//                'label' => 'капитализация',
//                'format' => 'raw',
//                'value' => function (IssuerRating $model) {
//                    return Html::tag('div', SimpleNumberFormatter::toView(CapitalizationCalculator::calculate($model)), []);
//                }
//            ],
//            [
//                'headerOptions' => ['data-tooltip' => 'Капитализация к прибыли (окупаемость в годах)'],
//                'label' => 'P/E',
//                'format' => 'raw',
//                'value' => function (IssuerRating $model) {
//                    $values = '';
//                    foreach ($model->getIndicator() as $indicator) {
//                        $values .= GoodBadValueViewHelper::execute($indicator['PE'], line: 10, moreBetter: false) . "<br>";
//                    }
//
//                    return $values;
//                }
//            ],
//            [
//                'headerOptions' => ['data-tooltip' => 'Капитализация к капиталу (чистых рублей на вложенный рубль)'],
//                'label' => 'P/B',
//                'format' => 'raw',
//                'value' => function (IssuerRating $model) {
//                    $values = '';
//                    foreach ($model->getIndicator() as $indicator) {
//                        $values .= GoodBadValueViewHelper::execute($indicator['PB'], line: 1) . "<br>";
//                    }
//
//                    return $values;
//                }
//            ],
//            [
//                'headerOptions' => ['data-tooltip' => 'https://www.youtube.com/watch?v=JnT3XThmzgA'],
//                'label' => 'экспресс оценка',
//                'format' => 'raw',
//                'value' => function (IssuerRating $model) {
//                    $values = '';
//                    foreach (ExpressRatingCalculator::calculate($model) as $mark) {
//                        $values .= GoodBadValueViewHelper::execute($mark, line: 5, decimals: 1) . "<br>";
//                    }
//
//                    return $values;
//                }
//            ],
        ],
    ]) ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
    });
');
