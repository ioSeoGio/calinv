<?php
/** @var PersonalTokenForm $personalTokenForm */

use app\controllers\Portfolio\Form\PersonalTokenForm;
use app\models\FinancialInstrument\Token;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'id' => 'login-form',
    'action' => Url::to(['Portfolio/personal-token/create']),
    'validationUrl' => Url::to(['Portfolio/personal-token/validate']),

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
                    <?= $form->field($personalTokenForm, 'token_id')
                        ->dropDownList(
                            items: ArrayHelper::map(Token::find()->all(),
                            from: function (Token $model) {return (string) $model->_id;},
                            to: fn (Token $bond) => $bond->issuerRating?->issuer .' - '.$bond->name,
                        ),
                        options: ['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($personalTokenForm, 'amount')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($personalTokenForm, 'buyPrice')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($personalTokenForm, 'buyDate')->textInput(['class' => 'form-control', 'placeholder' => 'дд.мм.гг'])->label(false) ?>
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
