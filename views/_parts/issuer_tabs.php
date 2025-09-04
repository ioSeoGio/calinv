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
            'url' => ['/issuer/view', 'unp' => $model->_pid],
            'active' => $url === Url::to(['/issuer/view', 'unp' => $model->_pid]),
        ],
        [
            'label' => 'Коэффициенты',
            'url' => ['/coefficient/view', 'unp' => $model->_pid],
            'active' => str_contains($url, Url::to(['/coefficient/view'])),
        ],
        [
            'label' => 'Бухгалтерский баланс',
            'url' => ['/accounting-balance/index', 'unp' => $model->_pid],
            'active' => str_contains($url, Url::to(['/accounting-balance/index'])),
        ],
        [
            'label' => 'Отчет о прибылях и убытках',
            'url' => ['/profit-loss-report/index', 'unp' => $model->_pid],
            'active' => str_contains($url, Url::to(['/profit-loss-report/index'])),
        ],
        [
            'label' => 'Отчет о движении денежных средств',
            'url' => ['/cash-flow-report/index', 'unp' => $model->_pid],
            'active' => str_contains($url, Url::to(['/cash-flow-report/index'])),
        ],
    ]
]) ?>
