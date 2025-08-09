<?php

use miloschuman\highcharts\Highcharts;
use src\Entity\Share\Deal\ShareDealRecord;
use src\Entity\Share\Share;
use yii\helpers\ArrayHelper;

/** @var Share $share */
/** @var ShareDealRecord[] $shareDeals */

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

</div>
