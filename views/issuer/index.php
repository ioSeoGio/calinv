<?php

/** @var yii\web\View $this */
/** @var ActiveDataProvider $dataProvider */
/** @var IssuerCreateForm $createForm */
/** @var IssuerSearchForm $searchForm */

use app\widgets\GuardedActionColumn;
use src\Action\Issuer\IssuerCreateForm;
use src\Action\Issuer\IssuerSearchForm;
use src\Entity\Issuer\Issuer;
use yii\bootstrap5\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Калькулятор эмитентов';
?>
<?= $this->render('tabs', []); ?>
<div class="calculator-index">
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
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchForm,
        'columns' => [
            [
                'label' => 'эмитент',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return Html::a($model->name, ['/issuer/view', 'id' => $model->id]);
                }
            ],
            '_legalStatus',
            '_pid',
            [
                'label' => 'BIK рейтинг',
                'value' => function (Issuer $model) {
                    return $model->businessReputationInfo?->rating->value;
                }
            ],
            [
                'label' => 'Недобросовестный поставщик',
                'value' => function (Issuer $model) {
                    return $model->unreliableSupplier !== null ? 'Да' : 'Нет';
                }
            ],
            [
                'class' => GuardedActionColumn::class,
                'showButtons' => ['view'],
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
