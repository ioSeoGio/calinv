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
            'label' => 'Рейтинг деловой репутации BIK',
            'url' => ['/issuer/business-rating'],
            'active' => str_contains($url, '/issuer/business-rating'),
        ],
        [
            'label' => 'ESG рейтинг BIK',
            'url' => ['/issuer/esg-rating'],
            'active' => str_contains($url, '/issuer/esg-rating'),
        ],
        [
            'label' => 'Реестр недобросовестных поставщиков',
            'url' => ['/issuer/unreliable-supplier'],
            'active' => str_contains($url, '/issuer/unreliable-supplier'),
        ],
    ]
]) ?>
