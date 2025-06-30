<?php

use yii\bootstrap5\Tabs;
use yii\helpers\Url;

$url = Url::current();
?>

<?= Tabs::widget([
    'headerOptions' => ['class' => 'd-flex justify-content-center'],
    'items' => [
        [
            'label' => 'Эмитенты',
            'url' => ['/issuer/index'],
            'active' => str_contains($url, '/issuer/index') || $url === '/issuer' || $url === '/',
        ],
        [
            'label' => 'Рейтинг BIK',
            'url' => ['/issuer/rating'],
            'active' => str_contains($url, '/issuer/rating'),
        ],
    ]
]) ?>
