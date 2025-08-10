<?php

use yii\bootstrap5\Tabs;
use yii\helpers\Url;

?>
<?= Tabs::widget([
    'headerOptions' => ['class' => 'd-flex justify-content-center'],
    'items' => [
        [
            'label' => 'Акции (активные)',
            'url' => Url::to(['/share/index']),
            'active' => str_starts_with(Url::current(), '/share') || Url::current() === '/share/index',
        ],
        [
            'label' => 'Акции (все)',
            'url' => Url::to(['/share/all-shares']),
            'active' => str_contains(Url::current(), '/share/all-shares'),
        ],
        [
            'label' => 'График всех акций',
            'url' => Url::to(['/share/all-shares-compare']),
            'active' => str_contains(Url::current(), '/share/all-shares-compare'),
        ],
    ]
]) ?>