<?php

use yii\bootstrap5\Tabs;
use yii\helpers\Url;

?>
<?= Tabs::widget([
    'headerOptions' => ['class' => 'd-flex justify-content-center'],
    'items' => [
        [
            'label' => 'Акции',
            'url' => Url::to(['/Portfolio/personal-share/index']),
            'active' => str_contains(Url::current(), '/portfolio'),
        ],
        [
            'label' => 'Облигации',
            'url' => Url::to(['/Portfolio/personal-bond/index']),
            'active' => str_contains(Url::current(), Url::to(['/Portfolio/personal-bond/index'])),
        ],
        [
            'label' => 'Токены',
            'url' => Url::to(['/Portfolio/personal-token/index']),
            'active' => str_contains(Url::current(), Url::to(['/Portfolio/personal-token/index'])),
        ],
    ]
]) ?>
