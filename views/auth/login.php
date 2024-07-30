<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="auth-form">
	<h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

	<?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'Электронная почта'])->label(false) ?>

	<?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Пароль'])->label(false) ?>

	<?= $form->field($model, 'rememberMe')->checkbox()->label('Запомнить меня') ?>

	<div class="form-group text-center">
		<?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>
