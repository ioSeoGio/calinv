<?php
/** @var TokenForm $tokenForm */

use app\controllers\FinancialInstrument\Form\TokenForm;
use app\models\IssuerRating\IssuerRating;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'id' => 'login-form',
    'action' => Url::to(['FinancialInstrument/token/create']),
    'validationUrl' => Url::to(['FinancialInstrument/token/validate']),

    'enableAjaxValidation'      => true,
    'enableClientValidation'    => true,
    'validateOnChange'          => true,
    'validateOnSubmit'          => true,
    'validateOnBlur'            => true,
]); ?>
    <table class="table">
        <tr>
            <th scope="col">
                Имя выпуска
            </th>
            <th scope="col">
                Эмитент
            </th>
            <th scope="col">
                Номинал, руб.
            </th>
            <th scope="col">
                Ставка %
            </th>
            <th scope="col">
                Текущая цена, руб.
            </th>
            <th scope="col">
                Выпущено, шт.
            </th>
            <th scope="col">
            </th>
        </tr>
        <tr>
            <td>
                <div class="input-group mb-3">
                    <?= $form->field($tokenForm, 'name')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($tokenForm, 'issuer_id')
                        ->dropDownList(
                            items: ArrayHelper::map(IssuerRating::find()->all(),
                            from: function (IssuerRating $model) {return (string) $model->_id;},
                            to: 'issuer'
                        ),
                        options: ['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($tokenForm, 'denomination')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($tokenForm, 'rate')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($tokenForm, 'currentPrice')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($tokenForm, 'volumeIssued')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($tokenForm, 'issueDate')->textInput(['class' => 'form-control', 'placeholder' => 'дд.мм.гг'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($tokenForm, 'maturityDate')->textInput(['class' => 'form-control', 'placeholder' => 'дд.мм.гг'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="mx-auto d-flex justify-content-center">
                    <button class="btn btn-primary" type="submit">Токен</button>
                </div>
            </td>
        </tr>
    </table>
<?php ActiveForm::end() ?>
