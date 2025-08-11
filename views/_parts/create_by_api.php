<?php
/** @var FinancialReportByApiCreateForm $createForm */
/** @var Issuer $issuer */
/** @var string $url */

use src\Action\Issuer\FinancialReport\FinancialReportByApiCreateForm;
use src\Entity\Issuer\Issuer;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'action' => Url::to([$url, 'issuerId' => $issuer->id]),

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
                    <button class="btn btn-danger" type="submit">Обновить через Legat (платно)</button>
                </div>
            </td>
        </tr>
    </table>
<?php ActiveForm::end() ?>
