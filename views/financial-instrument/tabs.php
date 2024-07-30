<?php

use yii\bootstrap5\Tabs;
use yii\helpers\Url;

?>
<?= Tabs::widget([
    'headerOptions' => ['class' => 'd-flex justify-content-center'],
    'items' => [
        [
            'label' => 'Акции',
            'url' => Url::to(['/FinancialInstrument/share/index']),
            'active' => str_contains(Url::current(), '/financial-instruments'),
        ],
        [
            'label' => 'Облигации',
            'url' => Url::to(['/FinancialInstrument/bond/index']),
            'active' => str_contains(Url::current(), Url::to(['/FinancialInstrument/bond/index'])),
        ],
        [
            'label' => 'Токены',
            'url' => Url::to(['/FinancialInstrument/token/index']),
            'active' => str_contains(Url::current(), Url::to(['/FinancialInstrument/token/index'])),
        ],
    ]
]) ?>
