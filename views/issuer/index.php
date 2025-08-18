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
use src\ViewHelper\ComplexRating\ComplexRatingViewHelper;
use src\ViewHelper\ExpressRating\ExpressRatingViewHelper;
use src\ViewHelper\IssuerIcon\Button\ToggleVisibilityIconPrinter;
use src\ViewHelper\IssuerIcon\IssuerStateIconsPrinter;
use src\ViewHelper\IssuerRating\IssuerBikRatingViewHelper;
use yii\bootstrap5\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Эмитенты';
?>
<?= $this->render('tabs', []); ?>
<div class="calculator-index">

    <?php if (Yii::$app->user->can(UserRole::admin->value)): ?>
        <?= $this->render('create', [
            'createForm' => $createForm,
        ]) ?>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'class' => \yii\bootstrap5\LinkPager::class,
        ],
        'filterModel' => $searchForm,
        'columns' => [
            [
                'label' => 'Важное',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return IssuerStateIconsPrinter::printMany($model);
                }
            ],
            [
                'label' => 'Эмитент',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    $result = ToggleVisibilityIconPrinter::print($model);
                    $result .= Html::a($model->name, ['/issuer/view', 'id' => $model->id]);

                    return $result;
                }
            ],
            '_legalStatus',
            [
                'attribute' => '_pid',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return DetailViewCopyHelper::renderValueColored($model->_pid);
                }
            ],
            [
                'label' => 'Выпуски акций',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return Html::a("Активные ({$model->getActiveShares()->count()})", ['/share', 'ShareSearchForm' => [
                            'issuerId' =>  $model->id,
                        ]], ['target' => '_blank', 'class' => 'btn btn-primary'])
                        . '<hr>'
                        . Html::a("Все ({$model->getShares()->count()})", ['/share/all-shares', 'ShareSearchForm' => [
                            'issuerId' =>  $model->id,
                        ]], ['target' => '_blank', 'class' => 'btn btn-secondary']);
                },
                'contentOptions' => ['style' => 'min-width: 140px;'],
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
                'contentOptions' => ['style' => 'min-width: 115px;'],
            ],
            [
                'label' => 'k2',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\IssuerCoefficient\K2ViewHelper::render($model);
                },
                'contentOptions' => ['style' => 'min-width: 115px;'],
            ],
            [
                'label' => 'k3',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\IssuerCoefficient\K3ViewHelper::render($model);
                },
                'contentOptions' => ['style' => 'min-width: 115px;'],
            ],
            [
                'label' => 'BIK',
                'format' => 'html',
                'value' => function (Issuer $model) {
                    return IssuerBikRatingViewHelper::render($model);
                }
            ],
            [
                'header' => '<span title="Экспресс балл">ЭБ</span> '
                    . Html::a(Icon::printFaq(), Url::to(['/faq#express-rating'])),
                'headerOptions' => ['encode' => false],
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return ExpressRatingViewHelper::render($model, false);
                },
                'contentOptions' => ['style' => 'min-width: 95px;'],
            ],
            [
                'header' => '<span title="Комплексный балл">КБ</span> '
                    . Html::a(Icon::printFaq(), Url::to(['/faq#complex-rating'])),
                'headerOptions' => ['encode' => false],
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return ComplexRatingViewHelper::render($model);
                },
                'contentOptions' => ['style' => 'min-width: 95px;'],
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
        ],
    ]) ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
    });
');
