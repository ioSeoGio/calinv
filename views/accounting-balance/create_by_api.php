<?php
/** @var AccountingBalanceApiCreateForm $createForm */
/** @var Issuer $issuer */

use src\Action\Issuer\AccountingBalance\AccountingBalanceApiCreateForm;
use src\Entity\Issuer\Issuer;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;

$form = ActiveForm::begin([
    'action' => Url::to(['/accounting-balance/fetch-external', 'issuerId' => $issuer->id]),

    'enableAjaxValidation'      => false,
    'enableClientValidation'    => true,
    'validateOnChange'          => true,
    'validateOnSubmit'          => true,
    'validateOnBlur'            => true,
]); ?>
    <table class="table">
        <tr>
            <th class="col-xs">
                Год
            </th>
            <th scope="col">
            </th>
        </tr>
        <tr>
            <td class="col-xs">
                <?= $form->field($createForm, 'year', [
                    'inputOptions' => [
                        'maxlength' => 4,
                    ],
                ])->textInput(['class' => 'form-control'])->label(false) ?>
            </td>
            <td>
                <div class="mx-auto d-flex justify-content-center">
                    <button class="btn btn-primary" type="submit">Обновить через API</button>
                </div>
            </td>
        </tr>
    </table>
<?php ActiveForm::end() ?>
