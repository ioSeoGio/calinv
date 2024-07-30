<?php
/** @var BondForm $bondForm */

use app\controllers\FinancialInstrument\Form\BondForm;
use app\models\IssuerRating\IssuerRating;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'id' => 'login-form',
    'action' => Url::to(['FinancialInstrument/bond/create']),
    'validationUrl' => Url::to(['FinancialInstrument/bond/validate']),

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
                Выпуск
            </th>
            <th scope="col">
                Погашение
            </th>
            <th scope="col">
            </th>
        </tr>
        <tr>
            <td>
                <div class="input-group mb-3">
                    <?= $form->field($bondForm, 'name')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($bondForm, 'issuer_id')
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
                    <?= $form->field($bondForm, 'denomination')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($bondForm, 'rate')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($bondForm, 'currentPrice')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($bondForm, 'volumeIssued')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($bondForm, 'issueDate')->textInput(['class' => 'form-control', 'placeholder' => 'дд.мм.гг'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($bondForm, 'maturityDate')->textInput(['class' => 'form-control', 'placeholder' => 'дд.мм.гг'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="mx-auto d-flex justify-content-center">
                    <button class="btn btn-primary" type="submit">Облигация</button>
                </div>
            </td>
        </tr>
    </table>
<?php ActiveForm::end() ?>
