<?php
/** @var PersonalBondForm $personalBondForm */

use app\controllers\Portfolio\Form\PersonalBondForm;
use app\models\FinancialInstrument\Bond;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'id' => 'login-form',
    'action' => Url::to(['Portfolio/personal-bond/create']),
    'validationUrl' => Url::to(['Portfolio/personal-bond/validate']),

    'enableAjaxValidation'      => true,
    'enableClientValidation'    => true,
    'validateOnChange'          => true,
    'validateOnSubmit'          => true,
    'validateOnBlur'            => true,
]); ?>
    <table class="table">
        <tr>
            <th scope="col">
                Выпуск
            </th>
            <th scope="col">
                Кол-во в портфеле, шт.
            </th>
            <th scope="col">
                Закупочная цена, руб.
            </th>
            <th scope="col">
            </th>
        </tr>
        <tr>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($personalBondForm, 'bond_id')
                        ->dropDownList(
                            items: ArrayHelper::map(Bond::find()->all(),
                            from: function (Bond $model) {return (string) $model->_id;},
                            to: fn (Bond $bond) => $bond->issuerRating?->issuer .' - '.$bond->name,
                        ),
                        options: ['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($personalBondForm, 'amount')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($personalBondForm, 'buyPrice')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($personalBondForm, 'buyDate')->textInput(['class' => 'form-control', 'placeholder' => 'дд.мм.гг'])->label(false) ?>
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
