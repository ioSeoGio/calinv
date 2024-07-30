<?php

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

NavBar::begin([
	'brandLabel' => Yii::$app->name,
	'brandUrl' => Yii::$app->homeUrl,
	'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
]);

echo '<div class="d-flex justify-content-between w-100">';

echo Nav::widget([
	'options' => ['class' => 'navbar-nav'],
	'items' => [
		['label' => 'Калькулятор эмитентов', 'url' => ['/calculator']],
		['label' => 'Мой портфель', 'url' => ['/portfolio']],
		['label' => 'Инструменты', 'url' => ['/financial-instruments']],
	]
]);

if (Yii::$app->user->isGuest) {
	echo Nav::widget([
		'options' => ['class' => 'navbar-nav'],
		'items' => [
			['label' => 'Вход', 'url' => ['/login']],
			['label' => 'Регистрация', 'url' => ['/auth/signup']],
		]
	]);
} else {
	echo Nav::widget([
		'options' => ['class' => 'navbar-nav'],
		'items' => [
			['label' => 'Профиль', 'url' => ['/profile/index']],
			'<li>'
			. Html::beginForm(['/auth/logout'], 'post', ['class' => 'form-inline'])
			. Html::submitButton(
				'Выход (' . Yii::$app->user->identity->username . ')',
				['class' => 'btn btn-link logout']
			)
			. Html::endForm()
			. '</li>'
		]
	]);
}

echo '</div>';

NavBar::end();

