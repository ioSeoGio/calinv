<?php

use src\Entity\Issuer\Issuer;
use yii\bootstrap5\Tabs;
use yii\helpers\Url;

/**
 * @var Issuer $model
 */

$url = Url::current();
?>

<?= Tabs::widget([
    'headerOptions' => ['class' => 'd-flex justify-content-center'],
    'items' => [
        [
            'label' => 'Общее',
            'url' => ['/issuer/view', 'id' => $model->id],
            'active' => $url === Url::to(['/issuer/view', 'id' => $model->id]),
        ],
        [
            'label' => 'Бухгалтерский баланс',
            'url' => ['/accounting-balance/index', 'issuerId' => $model->id],
            'active' => str_contains($url, Url::to(['/accounting-balance/index', 'issuerId' => $model->id])),
        ],
    ]
]) ?>
