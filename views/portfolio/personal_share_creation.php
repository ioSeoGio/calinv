<?php
/** @var \src\Action\Share\PersonalShareCreateForm $personalShareCreateForm */

use kartik\number\NumberControl;
use kartik\select2\Select2;
use src\Entity\Issuer\Issuer;
use src\Entity\Share\Share;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'action' => Url::to(['/personal-share/create']),
    'validationUrl' => Url::to(['/personal-share/validate']),

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
                    <?= $form->field($personalShareCreateForm, 'share_id')
                        ->widget(Select2::class, [
                            'data' => ArrayHelper::map(Share::findActive()->with('issuer')->all(),
                                from: fn (Share $model) => (string) $model->id,
                                to: fn (Share $model) => $model->getFormattedNameWithIssuer(),
                            ),
                            'options' => [
                                'placeholder' => 'Выберите купленную акцию',
                                'class' => 'form-control',
                                'style' => 'min-width: 400px; width: 100%;',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false,
                            ],
                        ])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($personalShareCreateForm, 'amount')->textInput(['class' => 'form-control'])->label(false) ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($personalShareCreateForm, 'buyPrice')->widget(
                            NumberControl::class,
                            Yii::$app->params['moneyWidgetOptions']
                    )->label(false); ?>
                </div>
            </td>
            <td>
                <div class="input-group mb-1">
                    <?= $form->field($personalShareCreateForm, 'boughtAt')->textInput(['class' => 'form-control', 'placeholder' => 'дд.мм.гг'])->label(false) ?>
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
