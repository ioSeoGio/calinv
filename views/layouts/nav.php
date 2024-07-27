<?php

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => [
        ['label' => 'Калькулятор эмитентов', 'url' => ['/calculator']],
        ['label' => 'Мой портфель', 'url' => ['/portfolio']],
        ['label' => 'Инструменты', 'url' => ['/financial-instruments']],
    ]
]);
NavBar::end();
