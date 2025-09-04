<?php

/** @var SignupForm $model */

use src\Action\Auth\SignUpForm;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
    $('body').on('click', '.password-checkbox', function(){
        if ($(this).is(':checked')){
            $('#password-input').attr('type', 'text');
            $('#password-repeat-input').attr('type', 'text');
        } else {
            $('#password-input').attr('type', 'password');
            $('#password-repeat-input').attr('type', 'password');
        }
    }); 
")
?>

<div class="sign-up-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'form-signup',
        'validateOnChange'          => false,
        'validateOnSubmit'          => true,
        'validateOnBlur'            => false,
    ]); ?>

    <?= $form->field($model, 'nickname')->textInput(['placeholder' => 'Никнейм'])->label(false) ?>
    <?= $form->field($model, 'email')->textInput(['placeholder' => 'Электронная почта'])->label(false) ?>
    <?= $form->field($model, 'password')->passwordInput(['id' => 'password-input', 'placeholder' => 'Пароль'])->label(false) ?>
    <?= $form->field($model, 'passwordRepeat')->passwordInput(['id' => 'password-repeat-input', 'placeholder' => 'Повторите пароль'])->label(false) ?>
    <label><input type="checkbox" class="password-checkbox"> Показать пароль</label>

    <div class="form-group text-center">
        <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary btn-block', 'name' => 'signup-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>