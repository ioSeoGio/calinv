<?php
/** @var AccountingBalanceCreateForm $accountingBalanceCreateForm */
/** @var Issuer $issuer */

use app\widgets\MaskedNumberInput;
use kartik\number\NumberControl;
use src\Action\Issuer\AccountingBalance\AccountingBalanceCreateForm;
use src\Entity\Issuer\FinanceTermType;
use src\Entity\Issuer\Issuer;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'login-form',
    'action' => Url::to(['/accounting-balance/create', 'issuerId' => $issuer->id]),
    'validationUrl' => Url::to(['/accounting-balance/validate', 'issuerId' => $issuer->id]),

    'enableAjaxValidation'      => true,
    'enableClientValidation'    => true,
    'validateOnChange'          => true,
    'validateOnSubmit'          => true,
    'validateOnBlur'            => true,
]); ?>
    <table class="table">
        <tr>
            <th scope="col">
                Год
            </th>
            <th scope="col">
                Вид отчета
            </th>
            <th scope="col">
                Долгосрочные активы, т.р.
            </th>
            <th scope="col">
                Краткосрочные активы, т.р.
            </th>
            <th scope="col">
                Долгосрочные обязательства, т.р.
            </th>
            <th scope="col">
                Краткосрочные обязательства, т.р.
            </th>
            <th scope="col">
                Капитал, т.р.
            </th>
            <th scope="col">
            </th>
        </tr>
        <tr>
            <td>
                <?= $form->field($accountingBalanceCreateForm, 'year')->textInput(['class' => 'form-control'])->label(false) ?>
            </td>
            <td>
                <?= $form->field($accountingBalanceCreateForm, 'termType')
                    ->dropDownList(
                        FinanceTermType::toFrontend(),
                        options: ['class' => 'form-control'],
                    )->label(false) ?>
            </td>
            <td>
                <?= $form->field($accountingBalanceCreateForm, 'longAsset')->widget(
                        NumberControl::class,
                        Yii::$app->params['moneyWidgetOptions']
                )->label(false); ?>
            </td>
            <td>
                <?= $form->field($accountingBalanceCreateForm, 'shortAsset')->widget(
                        NumberControl::class,
                        Yii::$app->params['moneyWidgetOptions']
                )->label(false); ?>
            </td>
            <td>
                <?= $form->field($accountingBalanceCreateForm, 'longDebt')->widget(
                        NumberControl::class,
                        Yii::$app->params['moneyWidgetOptions']
                )->label(false); ?>
            </td>
            <td>
                <?= $form->field($accountingBalanceCreateForm, 'shortDebt')->widget(
                        NumberControl::class,
                        Yii::$app->params['moneyWidgetOptions']
                )->label(false); ?>
            </td>
            <td>
                <?= $form->field($accountingBalanceCreateForm, 'capital')->widget(
                        NumberControl::class,
                        Yii::$app->params['moneyWidgetOptions']
                )->label(false); ?>
            </td>
            <td>
                <div class="mx-auto d-flex justify-content-center">
                    <button class="btn btn-primary" type="submit">Сохранить</button>
                </div>
            </td>
        </tr>
    </table>
<?php ActiveForm::end() ?>
