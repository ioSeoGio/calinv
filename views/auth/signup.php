<?php

/** @var SignupForm $model */

use app\models\SignupForm;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="auth-form">
	<h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

	<?= $form->field($model, 'email')->textInput(['placeholder' => 'Электронная почта'])->label(false) ?>

	<?= $form->field($model, 'username')->textInput(['placeholder' => 'Логин'])->label(false) ?>

	<?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Пароль'])->label(false) ?>

	<?= $form->field($model, 'password_repeat')->passwordInput(['placeholder' => 'Повторите пароль'])->label(false) ?>

	<div class="form-group text-center">
	  <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary btn-block', 'name' => 'signup-button']) ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>
