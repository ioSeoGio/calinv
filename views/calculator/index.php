<?php

/** @var yii\web\View $this */
/** @var CalculateForm $model */

use app\controllers\IssuerRatingCalculator\CalculateForm;
use app\widgets\BaseMultipleInputWidget;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = 'IssuerRatingCalculator';
?>
<div class="calculator-index">
    <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'action' => Url::to(['IssuerRatingCalculator/calculator/calculate']),

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
                    Активы длинные
                </th>
                <th scope="col">
                    / короткие, т.руб
                </th>
                <th scope="col">
                    Обязательства длинные
                </th>
                <th scope="col">
                    / короткие, т.руб
                </th>
                <th scope="col">
                    Прибыль
                </th>
                <th scope="col">
                    Капитал
                </th>
                <th scope="col">
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
                        <?= $form->field($model, 'issuer')->textInput(['class' => 'form-control'])->label(false) ?>
                    </div>
                </td>
                <td>
                    <div class="input-group mb-1">
                        <?= $form->field($model, 'bikScore')->textInput(['class' => 'form-control'])->label(false) ?>
                    </div>
                </td>
                <td>
                    <?= BaseMultipleInputWidget::widget([
                        'form' => $form,
                        'fieldName' => 'shortAssetPerYear',
                        'model' => $model,
                        'options' => ['id' => 'shortAssetPerYear'],
                    ]) ?>
                </td>
                <td>
                    <?= BaseMultipleInputWidget::widget([
                        'form' => $form,
                        'fieldName' => 'longAssetPerYear',
                        'model' => $model,
                        'options' => ['id' => 'longAssetPerYear'],
                    ]) ?>
                </td>
                <td>
                   <?= BaseMultipleInputWidget::widget([
                        'form' => $form,
                        'fieldName' => 'shortLiabilityPerYear',
                        'model' => $model,
                   ]) ?>
                </td>
                <td>
                   <?= BaseMultipleInputWidget::widget([
                        'form' => $form,
                        'fieldName' => 'longLiabilityPerYear',
                        'model' => $model,
                   ]) ?>
                </td>
                <td>
                   <?= BaseMultipleInputWidget::widget([
                        'form' => $form,
                        'fieldName' => 'profitPerYear',
                        'model' => $model,
                   ]) ?>
                </td>
                <td>
                   <?= BaseMultipleInputWidget::widget([
                        'form' => $form,
                        'fieldName' => 'capitalPerYear',
                        'model' => $model,
                   ]) ?>
                </td>
                <td>
                    <div>
                        <button class="btn btn-outline-success" id="add-fields-row" type="button">+</button>
                        <button class="btn btn-outline-danger" id="remove-fields-row" type="button">-</button>
                    </div>
                </td>
                <td>
                    <div class="input-group mb-1">
                        <?= $form->field($model, 'k1_standard')->textInput(['class' => 'form-control'])->label(false) ?>
                    </div>
                </td>
                <td>
                    <div class="input-group mb-1">
                        <?= $form->field($model, 'k2_standard')->textInput(['class' => 'form-control'])->label(false) ?>
                    </div>
                </td>
                <td>
                    <div class="input-group mb-1">
                        <?= $form->field($model, 'k3_standard')->textInput(['class' => 'form-control'])->label(false) ?>
                    </div>
                </td>
            </tr>
            <div class="mx-auto d-flex justify-content-center">
                <button class="btn btn-primary" type="submit">Рассчитать</button>
            </div>
        </table>
    <?php ActiveForm::end() ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
        $("#add-fields-row").on("click", function() {
            $(".multiple-input").each( function () {
                $(this).multipleInput("add", {})
            })
            $(".list-cell__button").css("display","none")
        })
        $("#remove-fields-row").on("click", function() {
            $(".multiple-input").each( function () {
                $(this).multipleInput("remove")
            })
            $(".list-cell__button").css("display","none")
        })
        
        $(".list-cell__button").css("display","none")
    });
');
