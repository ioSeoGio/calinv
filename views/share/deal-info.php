<?php

use miloschuman\highcharts\Highcharts;
use src\Entity\Share\Deal\ShareDealRecord;
use src\Entity\Share\Share;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var Share $share */
/** @var ShareDealRecord[] $shareDeals */
/** @var \yii\data\ActiveDataProvider<ShareDealRecord> $dataProvider */

/** @var ShareDealRecord[] $shareDeals */
$shareDeals = $dataProvider->query->all();

$this->title = 'Акция ' . $share->getFormattedNameWithIssuer();
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container my-5">
    <?= Highcharts::widget([
        'options' => [
            'title' => ['text' => 'График цены акции'],
            'xAxis' => [
                'categories' => ArrayHelper::getColumn($shareDeals, function (ShareDealRecord $element) {
                    return $element->date->format('d.m.Y');
                }),
            ],
            'yAxis' => [
                'title' => ['text' => 'Цена, р.']
            ],
            'series' => [
                ['name' => 'Минимальная цена', 'data' => ArrayHelper::getColumn($shareDeals, function (ShareDealRecord $element) {
                    return $element->minPrice;
                })],
                ['name' => 'Максимальная цена', 'data' => ArrayHelper::getColumn($shareDeals, function (ShareDealRecord $element) {
                    return $element->maxPrice;
                })],
                ['name' => 'Средневзвешенная цена', 'data' => ArrayHelper::getColumn($shareDeals, function (ShareDealRecord $element) {
                    return $element->weightedAveragePrice;
                })],
            ]
        ]
    ]); ?>
    <hr>
    <?= \src\ViewHelper\Tools\ShowMoreContainer::renderBtn('Остальные графики', 'otherChartsContainer'); ?>
    <hr>
    <div class="collapse" id="otherChartsContainer">
        <?= Highcharts::widget([
            'options' => [
                'title' => ['text' => 'Сумма всех сделок'],
                'xAxis' => [
                    'categories' => ArrayHelper::getColumn($shareDeals, function (ShareDealRecord $element) {
                        return $element->date->format('d.m.Y');
                    }),
                ],
                'yAxis' => [
                    'title' => ['text' => 'Всего, р.']
                ],
                'series' => [
                    ['name' => 'Сумма всех сделок', 'data' => ArrayHelper::getColumn($shareDeals, function (ShareDealRecord $element) {
                        return $element->totalSum;
                    })],
                ]
            ]
        ]); ?>
        <hr>
        <?= Highcharts::widget([
            'options' => [
                'title' => ['text' => 'Кол-во купленных акций'],
                'xAxis' => [
                    'categories' => ArrayHelper::getColumn($shareDeals, function (ShareDealRecord $element) {
                        return $element->date->format('d.m.Y');
                    }),
                ],
                'yAxis' => [
                    'title' => ['text' => 'Всего, шт.']
                ],
                'series' => [
                    ['name' => 'Кол-во купленных акций', 'data' => ArrayHelper::getColumn($shareDeals, function (ShareDealRecord $element) {
                        return $element->totalAmount;
                    })],
                ]
            ]
        ]); ?>
        <hr>
        <?= Highcharts::widget([
            'options' => [
                'title' => ['text' => 'Кол-во сделок'],
                'xAxis' => [
                    'categories' => ArrayHelper::getColumn($shareDeals, function (ShareDealRecord $element) {
                        return $element->date->format('d.m.Y');
                    }),
                ],
                'yAxis' => [
                    'title' => ['text' => 'Всего, шт.']
                ],
                'series' => [
                    ['name' => 'Кол-во сделок', 'data' => ArrayHelper::getColumn($shareDeals, function (ShareDealRecord $element) {
                        return $element->totalAmount;
                    })],
                ]
            ]
        ]); ?>
    </div>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'pager' => [
        'class' => \yii\bootstrap5\LinkPager::class,
    ],
    'columns' => [
        '_date:date',
        'currency',
        'minPrice',
        'maxPrice',
        'weightedAveragePrice',
        'totalSum',
        'totalAmount',
        'totalDealAmount',
    ],
]) ?>
