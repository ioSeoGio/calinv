<?php

use lib\FrontendHelper\Icon;
use src\Entity\User\UserRole;
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
		['label' => 'Итоги торгового дня', 'url' => ['/trading-day-result'], 'active' => str_contains(Url::current(), '/trading-day-result')],
	]
]);

$items = [];
if (!Yii::$app->user->isGuest) {
    $items[] = ['label' => 'Мой портфель', 'url' => ['/portfolio'], 'active' => str_ends_with(Url::current(), '/portfolio')];
}
$items[] = ['label' => 'Поиск портфелей', 'url' => ['/portfolio/search'], 'active' => str_contains(Url::current(), '/portfolio/search')];

echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => $items,
]);

$iconsContainer = function (string $icon, string $id): string {
    return Html::tag('div', $icon, [
        'id' => $id,
        'class' => 'ms-1',
        'style' => [
            'color' => 'whitesmoke',
            'display' => 'flex',
            'align-items' => 'center',
            'justify-content' => 'center',
        ],
    ]);
};

$darkThemeEnabled = Yii::$app->request->cookies->getValue('darkTheme', true);
$themeSwitcher = $iconsContainer(
    icon: $darkThemeEnabled
        ? Icon::print('bi bi-lightbulb')
        : Icon::print('bi bi-lightbulb-off'),
    id: 'theme-switcher',
);

$faq = $iconsContainer(
    Html::a(Icon::printFaq(), Url::to('/faq'), ['style' => ['color' => 'whitesmoke']]),
    'faq-link'
);

$logs = '';
if (Yii::$app->user->can(UserRole::admin->value)) {
    $logs = $iconsContainer(
        Html::a(Icon::print('bi bi-device-hdd'), Url::to('/log-reader'), ['style' => ['color' => 'whitesmoke']]),
        'logs-link'
    );
}

$telegram = $iconsContainer(
    Html::a(Icon::print('bi bi-telegram'), 'https://t.me/+VkMwRJV7LqoyZmYy', ['target' => '_blank']),
    'telegram-link'
);

$common = ''
    . $telegram
    . $logs
    . $faq
    . $themeSwitcher;

if (Yii::$app->user->isGuest) {
	echo Nav::widget([
		'options' => ['class' => 'navbar-nav'],
		'items' => [
            $common
            . Html::a('Вход', Url::to(['/login']), ['class' => 'nav-link'])
			. Html::a('Регистрация', Url::to(['/auth/signup']), ['class' => 'nav-link']),
		]
	]);
} else {
	echo Nav::widget([
		'options' => ['class' => 'navbar-nav'],
		'items' => [
            $common
			. Html::a('Профиль', Url::to(['/profile/index']), ['class' => 'nav-link'])
			. '<li>'
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

