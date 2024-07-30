<?php

/** @var yii\web\View $this */
/** @var CalculateSimpleForm $simpleForm */
/** @var CalculateIndicatorForm $indicatorForm */
/** @var ActiveDataProvider $dataProvider */
/** @var IssuerRatingSearchForm $searchForm */

use app\controllers\IssuerRatingCalculator\CalculateIndicatorForm;
use app\controllers\IssuerRatingCalculator\CalculateSimpleForm;
use app\controllers\IssuerRatingCalculator\IssuerRatingSearchForm;
use app\models\IssuerRating\IssuerRating;
use unclead\multipleinput\TabularColumn;
use unclead\multipleinput\TabularInput;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\Widget;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Калькулятор эмитентов';
?>
<div class="calculator-index">
    <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'action' => Url::to(['IssuerRatingCalculator/calculator/calculate']),
            'validationUrl' => Url::to(['IssuerRatingCalculator/calculator/validate']),

            'enableAjaxValidation'      => true,
            'enableClientValidation'    => true,
            'validateOnChange'          => true,
            'validateOnSubmit'          => true,
            'validateOnBlur'            => true,
    ]); ?>
        <table class="table">
            <tr>
                <th scope="col">
                    Эмитент
                </th>
                <th scope="col">
                    BIK рейтинг
                </th>
                <th scope="col">
                </th>
                <th scope="col">
                    Кол-во акций
                </th>
                <th scope="col">
                    k1
                </th>
                <th scope="col">
                    k2
                </th>
                <th scope="col">
                    k3
                </th>
            </tr>
            <tr>
                <td>
                    <div class="input-group mb-3">
                        <?= $form->field($simpleForm, 'issuer')->textInput(['class' => 'form-control'])->label(false) ?>
                    </div>
                </td>
                <td>
                    <div class="input-group mb-1">
                        <?= $form->field($simpleForm, 'bikScore')->textInput(['class' => 'form-control'])->label(false) ?>
                    </div>
                </td>
                <td>
                    <?= TabularInput::widget([
                        'modelClass' => CalculateIndicatorForm::class,
                        'form' => $form,
                        'min' => 2,
                        'max' => 15,
                        'addButtonOptions' => [
                            'label' => '+',
                            'class' => 'btn btn-outline-success',
                        ],
                        'removeButtonOptions' => [
                            'label' => '-',
                            'class' => 'btn btn-outline-danger',
                        ],
                        'attributeOptions' => [
                            'enableAjaxValidation' => true,
                            'enableClientValidation' => true,
                            'validateOnChange' => true,
                            'validateOnSubmit' => true,
                            'validateOnBlur' => true,
                        ],
                        'columns' => [
                            [
                                'name' => 'longAsset',
                                'type' => TabularColumn::TYPE_TEXT_INPUT,
                                'title' => 'Активы длинные',
                            ],
                            [
                                'name' => 'shortAsset',
                                'type' => TabularColumn::TYPE_TEXT_INPUT,
                                'title' => '/ короткие',
                            ],
                            [
                                'name' => 'longLiability',
                                'type' => TabularColumn::TYPE_TEXT_INPUT,
                                'title' => 'Долги длинные',
                            ],
                            [
                                'name' => 'shortLiability',
                                'type' => TabularColumn::TYPE_TEXT_INPUT,
                                'title' => '/ короткие',
                            ],
                            [
                                'name' => 'profit',
                                'type' => TabularColumn::TYPE_TEXT_INPUT,
                                'title' => 'Прибыль',
                            ],
                            [
                                'name' => 'capital',
                                'type' => TabularColumn::TYPE_TEXT_INPUT,
                                'title' => 'Капитал',
                            ],
                        ],
                    ]) ?>
                </td>
                <td>
                    <div class="input-group mb-1">
                        <?= $form->field($simpleForm, 'shareAmount')->textInput(['class' => 'form-control'])->label(false) ?>
                    </div>
                </td>
                <td>
                    <div class="input-group mb-1">
                        <?= $form->field($simpleForm, 'k1_standard')->textInput(['class' => 'form-control'])->label(false) ?>
                    </div>
                </td>
                <td>
                    <div class="input-group mb-1">
                        <?= $form->field($simpleForm, 'k2_standard')->textInput(['class' => 'form-control'])->label(false) ?>
                    </div>
                </td>
                <td>
                    <div class="input-group mb-1">
                        <?= $form->field($simpleForm, 'k3_standard')->textInput(['class' => 'form-control'])->label(false) ?>
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
                'attribute' => 'issuer',
            ],
            [
                'label' => 'BIK рейтинг',
                'attribute' => 'bikScore',
            ],
            [
                'attribute' => 'ср. прирост',
                'format' => 'raw',
                'value' => function (IssuerRating $model) {
                    $values = '';
                    foreach ($model->getAverageGrowth() as $key => $averageValue) {
                        if (str_contains($key, 'долги')) {
                            $class = $averageValue > 0 ? 'text-danger' : 'text-success';
                        } else {
                            $class = $averageValue > 0 ? 'text-success' : 'text-danger';
                        }

                        $values .= Html::tag(
                            name: 'div',
                            content: "$key: "
                            . Html::tag(
                                name: 'span',
                                content: round($averageValue, 2) . '%',
                                options: ['class' => $class]
                            ),
                            options: ['class' => 'text-primary']
                        );
                    }
                    return $values;
                }
            ],
            [
                'attribute' => 'мин. прирост',
                'format' => 'raw',
                'value' => function (IssuerRating $model) {
                    $values = '';
                    foreach ($model->getMinimumGrowth() as $key => $averageValue) {
                        if (str_contains($key, 'долги')) {
                            $class = $averageValue > 0 ? 'text-danger' : 'text-success';
                        } else {
                            $class = $averageValue > 0 ? 'text-success' : 'text-danger';
                        }

                        $values .= Html::tag(
                            name: 'div',
                            content: "$key: "
                            . Html::tag(
                                name: 'span',
                                content: round($averageValue, 2) . '%',
                                options: ['class' => $class]
                            ),
                            options: ['class' => 'text-primary']
                        );
                    }
                    return $values;
                }
            ],
            [
                'attribute' => 'k1',
                'format' => 'raw',
                'value' => function (IssuerRating $model) {
                    $values = '';
                    foreach ($model->getIndicator() as $indicator) {
                        $values .= Html::tag('div', round($indicator['k1'], 2), [
                            'class' => $indicator['k1'] > $model->k1_standard ? 'text-success' : 'text-danger',
                        ]);
                    }

                    return Html::tag('div', $model->k1_standard, ['class' => 'text-primary']) . $values;
                }
            ],
            [
                'attribute' => 'k2',
                'format' => 'raw',
                'value' => function (IssuerRating $model) {
                    $values = '';
                    foreach ($model->getIndicator() as $indicator) {
                        $values .= Html::tag('div', round($indicator['k2'], 2), [
                            'class' => $indicator['k2'] < $model->k2_standard ? 'text-success' : 'text-danger',
                        ]);
                    }

                    return Html::tag('div', $model->k2_standard, ['class' => 'text-primary']) . $values;
                }
            ],
            [
                'attribute' => 'k3',
                'format' => 'raw',
                'value' => function (IssuerRating $model) {
                    $values = '';
                    foreach ($model->getIndicator() as $indicator) {
                        $values .= Html::tag('div', round($indicator['k3'], 2), [
                            'class' => $indicator['k3'] > $model->k3_standard ? 'text-success' : 'text-danger',
                        ]);
                    }

                    return Html::tag('div', $model->k3_standard, ['class' => 'text-primary']) . $values;
                }
            ],
            [
                'label' => 'P/E',
                'format' => 'raw',
                'value' => function (IssuerRating $model) {
                    $values = '';
                    foreach ($model->getIndicator() as $indicator) {
                        $values .= Html::tag('div', round($indicator->PE, 2), [
                        ]);
                    }

                    return $values;
                }
            ],
        ],
    ]) ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
    });
');
