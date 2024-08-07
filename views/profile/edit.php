<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
/** @var \app\models\User $model */

$this->title = 'Редактирование профиля';
?>

<div class="profile-edit">
	<h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'username')->label('Логин') ?>
	<?= $form->field($model, 'email')->label('Электронная почта') ?>
	<?= $form->field($model, 'new_password')->passwordInput()->label('Новый пароль') ?>
	<?= $form->field($model, 'confirm_password')->passwordInput()->label('Повторите пароль') ?>

	<div class="form-group">
	  <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>
