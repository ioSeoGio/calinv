<?php

use yii\bootstrap5\Tabs;
use yii\helpers\Url;

?>
<?= Tabs::widget([
    'headerOptions' => ['class' => 'd-flex justify-content-center'],
    'items' => [
        [
            'label' => 'Акции',
            'url' => Url::to(['/share/index']),
            'active' => str_contains(Url::current(), '/share'),
        ],
    ]
]) ?>