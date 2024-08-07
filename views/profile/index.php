<?php

use yii\helpers\Html;

/** @var \app\models\User $model */

$this->title = 'Профиль';
?>

<div class="site-profile">
	<div class="container">
		<div class="row">
			<div class="col-md-8 offset-md-2">
				<div class="card">
					<div class="card-header bg-primary text-white">
						<h2><?= Html::encode($this->title) ?></h2>
					</div>
					<div class="card-body">
						<dl class="row">
							<dt class="col-sm-4">Логин:</dt>
							<dd class="col-sm-8"><?= Html::encode($model->username) ?></dd>

							<dt class="col-sm-4">Электронная почта:</dt>
							<dd class="col-sm-8"><?= Html::encode($model->email) ?></dd>

							<dt class="col-sm-4">Дата регистрации:</dt>
							<dd class="col-sm-8"><?= Yii::$app->formatter->asDate($model->created_at->toDateTime(), 'long') ?></dd>

						</dl>
					</div>
					<div class="card-footer text-end">
			  <?= Html::a('Редактировать', ['profile/edit'], ['class' => 'btn btn-primary']) ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
