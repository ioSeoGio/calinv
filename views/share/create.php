<?php
/** @var \src\Action\Share\ShareCreateForm $shareCreateForm */

use src\Entity\Issuer\Issuer;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'id' => 'login-form',
    'action' => Url::to(['/share/create']),
    'validationUrl' => Url::to(['/share/validate']),

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
                    <?= $form->field($shareCreateForm, 'name')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($shareCreateForm, 'issuer_id')
                        ->dropDownList(
                            items: ArrayHelper::map(Issuer::find()->all(),
                            from: function (Issuer $model) {
                                return (string) $model->id;
                            },
                            to: 'name'
                        ),
                        options: ['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($shareCreateForm, 'denomination')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($shareCreateForm, 'currentPrice')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($shareCreateForm, 'volumeIssued')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="mx-auto d-flex justify-content-center">
                    <button class="btn btn-primary" type="submit">Сохранить</button>
                </div>
            </td>
        </tr>
    </table>
<?php ActiveForm::end() ?>