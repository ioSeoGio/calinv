<?php
/** @var PersonalShareForm $personalShareForm */

use app\controllers\Portfolio\Form\PersonalShareForm;
use app\models\FinancialInstrument\Share;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'id' => 'login-form',
    'action' => Url::to(['Portfolio/personal-share/create']),
    'validationUrl' => Url::to(['Portfolio/personal-share/validate']),

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
                Дата покупки
            </th>
            <th scope="col">
            </th>
        </tr>
        <tr>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($personalShareForm, 'share_id')
                        ->dropDownList(
                            items: ArrayHelper::map(Share::find()->all(),
                            from: function (Share $model) {return (string) $model->_id;},
                            to: fn (Share $share) => $share->issuerRating?->issuer .' - '.$share->name,
                        ),
                        options: ['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($personalShareForm, 'amount')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($personalShareForm, 'buyPrice')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($personalShareForm, 'buyDate')->textInput(['class' => 'form-control', 'placeholder' => 'дд.мм.гг'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="mx-auto d-flex justify-content-center">
                    <button class="btn btn-primary" type="submit">Акция</button>
                </div>
            </td>
        </tr>
    </table>
<?php ActiveForm::end() ?>
