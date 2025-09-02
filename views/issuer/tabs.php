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
            'active' =>
                str_contains($url, '/issuer/index')
                || $url === '/issuer'
                || $url === '/'
                || str_contains($url, '/issuer?'),
        ],
        [
            'label' => 'Рейтинг деловой репутации BIK',
            'url' => ['/issuer-rating/business-rating'],
            'active' => str_contains($url, '/issuer-rating/business-rating'),
        ],
        [
            'label' => 'ESG рейтинг BIK',
            'url' => ['/issuer-rating/esg-rating'],
            'active' => str_contains($url, '/issuer-rating/esg-rating'),
        ],
        [
            'label' => 'Кредитный рейтинг BIK',
            'url' => ['/issuer-rating/credit-rating'],
            'active' => str_contains($url, '/issuer-rating/credit-rating'),
        ],
        [
            'label' => 'Реестр недобросовестных поставщиков',
            'url' => ['/issuer-rating/unreliable-supplier'],
            'active' => str_contains($url, '/issuer-rating/unreliable-supplier'),
        ],
    ]
]) ?>
