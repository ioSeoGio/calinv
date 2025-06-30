<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

NavBar::begin([
	'brandLabel' => Yii::$app->name,
	'brandUrl' => Yii::$app->homeUrl,
	'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
]);

echo '<div class="d-flex justify-content-between w-100">';

echo Nav::widget([
	'options' => ['class' => 'navbar-nav'],
	'items' => [
		['label' => 'Эмитенты', 'url' => ['/issuer'], 'active' => str_contains(Url::current(), '/issuer')],
		['label' => 'Акции', 'url' => ['/share'], 'active' => str_contains(Url::current(), '/share')],
	]
]);

echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => [
		['label' => 'Мой портфель', 'url' => ['/portfolio'], 'active' => Url::current() === '/portfolio' ],
		['label' => 'Поиск портфелей', 'url' => ['/portfolio/search'], 'active' => str_contains(Url::current(), '/portfolio/search')],
    ]
]);

if (Yii::$app->user->isGuest) {
	echo Nav::widget([
		'options' => ['class' => 'navbar-nav'],
		'items' => [
			['label' => 'Вход', 'url' => ['/login']],
//			['label' => 'Регистрация', 'url' => ['/auth/signup']],
		]
	]);
} else {
	echo Nav::widget([
		'options' => ['class' => 'navbar-nav'],
		'items' => [
//			['label' => 'Профиль', 'url' => ['/profile/index']],
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

