<?php

/** @var \src\Action\Issuer\IssuerCreateForm $createForm */

?>

<div class="card mt-3" style="min-width: 250px">
    <div class="card-body">
        <?php use yii\helpers\Html;
        use yii\helpers\Url;
        use yii\widgets\ActiveForm;

        $form = ActiveForm::begin([
            'id' => 'login-form',
            'action' => Url::to(['/issuer/create']),
            'validationUrl' => Url::to(['/issuer/validate']),

            'enableAjaxValidation' => true,
            'enableClientValidation' => true,
            'validateOnChange' => true,
            'validateOnSubmit' => true,
            'validateOnBlur' => true,
        ]); ?>
        <div class="input-group mb-1">
            <?= $form->field($createForm, 'pid')->textInput(['class' => 'form-control'])->label(false) ?>
        </div>
        <div class="input-group">
            <div class="me-3">
                <?= Html::submitButton('Рассчитать', [
                    'class' => 'btn btn-primary',
                    'name' => 'simple'
                ]) ?>
            </div>
            <div>
                <?= Html::submitButton('Рассчитать с автозаполнением Legat (платно)', [
                    'class' => 'btn btn-danger',
                    'name' => 'complex',
                ]) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>