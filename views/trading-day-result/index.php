<?php

/** @var User $model */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var DateTimeImmutable $lastAvailableDay */
/** @var TradingDayResultSearch $searchModel */

use src\Entity\User\User;
use src\Action\TradingDayResult\TradingDayResultSearch;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

$this->title = 'Итоги торгового дня';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="trading-day-result row justify-content-center">
    <?php if ($lastAvailableDay): ?>
        <div class="col-12 text-center">
            <h2>Торговый день: <?= Yii::$app->formatter->asDate($lastAvailableDay, 'full') ?></h2>
        </div>
        <hr>

        <!-- Search Form -->
        <div class="row col-xl-6 col-md-8 col-sm-12 mb-4">
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => ['class' => 'form-inline']
            ]); ?>

            <?= $form->field($searchModel, 'name')->textInput([
                'placeholder' => 'Поиск по названию акции...',
                'class' => 'form-control mr-2'
            ])->label(false) ?>

            <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Сбросить', ['index'], ['class' => 'btn btn-secondary ml-2']) ?>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="w-100"></div>

        <!-- Sorting Links -->
        <div class="row col-xl-6 col-md-8 col-sm-12 mb-3">
            <div class="sort-links d-flex flex-wrap justify-content-between">
                <?= Html::a('Название акции ' . ($dataProvider->sort->getAttributeOrder('name') == SORT_ASC ? '↑' : ($dataProvider->sort->getAttributeOrder('name') == SORT_DESC ? '↓' : '')), ['index', 'sort' => ($dataProvider->sort->getAttributeOrder('name') == SORT_ASC ? '-name' : 'name'), 'TradingDayResultSearch' => ['name' => $searchModel->name]], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                <?= Html::a('Эмитент ' . ($dataProvider->sort->getAttributeOrder('issuerName') == SORT_ASC ? '↑' : ($dataProvider->sort->getAttributeOrder('issuerName') == SORT_DESC ? '↓' : '')), ['index', 'sort' => ($dataProvider->sort->getAttributeOrder('issuerName') == SORT_ASC ? '-issuerName' : 'issuerName'), 'TradingDayResultSearch' => ['name' => $searchModel->name]], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                <?= Html::a('Рег. номер ' . ($dataProvider->sort->getAttributeOrder('registerNumber') == SORT_ASC ? '↑' : ($dataProvider->sort->getAttributeOrder('registerNumber') == SORT_DESC ? '↓' : '')), ['index', 'sort' => ($dataProvider->sort->getAttributeOrder('registerNumber') == SORT_ASC ? '-registerNumber' : 'registerNumber'), 'TradingDayResultSearch' => ['name' => $searchModel->name]], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                <?= Html::a('Макс. цена ' . ($dataProvider->sort->getAttributeOrder('selectedDayMaxPrice') == SORT_ASC ? '↑' : ($dataProvider->sort->getAttributeOrder('selectedDayMaxPrice') == SORT_DESC ? '↓' : '')), ['index', 'sort' => ($dataProvider->sort->getAttributeOrder('selectedDayMaxPrice') == SORT_ASC ? '-selectedDayMaxPrice' : 'selectedDayMaxPrice'), 'TradingDayResultSearch' => ['name' => $searchModel->name]], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                <?= Html::a('Мин. цена ' . ($dataProvider->sort->getAttributeOrder('selectedDayMinPrice') == SORT_ASC ? '↑' : ($dataProvider->sort->getAttributeOrder('selectedDayMinPrice') == SORT_DESC ? '↓' : '')), ['index', 'sort' => ($dataProvider->sort->getAttributeOrder('selectedDayMinPrice') == SORT_ASC ? '-selectedDayMinPrice' : 'selectedDayMinPrice'), 'TradingDayResultSearch' => ['name' => $searchModel->name]], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                <?= Html::a('Сред. цена ' . ($dataProvider->sort->getAttributeOrder('selectedDayPrice') == SORT_ASC ? '↑' : ($dataProvider->sort->getAttributeOrder('selectedDayPrice') == SORT_DESC ? '↓' : '')), ['index', 'sort' => ($dataProvider->sort->getAttributeOrder('selectedDayPrice') == SORT_ASC ? '-selectedDayPrice' : 'selectedDayPrice'), 'TradingDayResultSearch' => ['name' => $searchModel->name]], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                <?= Html::a('Общая сумма ' . ($dataProvider->sort->getAttributeOrder('selectedDayTotalSum') == SORT_ASC ? '↑' : ($dataProvider->sort->getAttributeOrder('selectedDayTotalSum') == SORT_DESC ? '↓' : '')), ['index', 'sort' => ($dataProvider->sort->getAttributeOrder('selectedDayTotalSum') == SORT_ASC ? '-selectedDayTotalSum' : 'selectedDayTotalSum'), 'TradingDayResultSearch' => ['name' => $searchModel->name]], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                <?= Html::a('Общий объем ' . ($dataProvider->sort->getAttributeOrder('selectedDayTotalAmount') == SORT_ASC ? '↑' : ($dataProvider->sort->getAttributeOrder('selectedDayTotalAmount') == SORT_DESC ? '↓' : '')), ['index', 'sort' => ($dataProvider->sort->getAttributeOrder('selectedDayTotalAmount') == SORT_ASC ? '-selectedDayTotalAmount' : 'selectedDayTotalAmount'), 'TradingDayResultSearch' => ['name' => $searchModel->name]], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                <?= Html::a('Кол-во сделок ' . ($dataProvider->sort->getAttributeOrder('selectedDayTotalDealAmount') == SORT_ASC ? '↑' : ($dataProvider->sort->getAttributeOrder('selectedDayTotalDealAmount') == SORT_DESC ? '↓' : '')), ['index', 'sort' => ($dataProvider->sort->getAttributeOrder('selectedDayTotalDealAmount') == SORT_ASC ? '-selectedDayTotalDealAmount' : 'selectedDayTotalDealAmount'), 'TradingDayResultSearch' => ['name' => $searchModel->name]], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                <?= Html::a('Макс. цена (пред.) ' . ($dataProvider->sort->getAttributeOrder('previousDayMaxPrice') == SORT_ASC ? '↑' : ($dataProvider->sort->getAttributeOrder('previousDayMaxPrice') == SORT_DESC ? '↓' : '')), ['index', 'sort' => ($dataProvider->sort->getAttributeOrder('previousDayMaxPrice') == SORT_ASC ? '-previousDayMaxPrice' : 'previousDayMaxPrice'), 'TradingDayResultSearch' => ['name' => $searchModel->name]], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                <?= Html::a('Мин. цена (пред.) ' . ($dataProvider->sort->getAttributeOrder('previousDayMinPrice') == SORT_ASC ? '↑' : ($dataProvider->sort->getAttributeOrder('previousDayMinPrice') == SORT_DESC ? '↓' : '')), ['index', 'sort' => ($dataProvider->sort->getAttributeOrder('previousDayMinPrice') == SORT_ASC ? '-previousDayMinPrice' : 'previousDayMinPrice'), 'TradingDayResultSearch' => ['name' => $searchModel->name]], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                <?= Html::a('Сред. цена (пред.) ' . ($dataProvider->sort->getAttributeOrder('previousDayPrice') == SORT_ASC ? '↑' : ($dataProvider->sort->getAttributeOrder('previousDayPrice') == SORT_DESC ? '↓' : '')), ['index', 'sort' => ($dataProvider->sort->getAttributeOrder('previousDayPrice') == SORT_ASC ? '-previousDayPrice' : 'previousDayPrice'), 'TradingDayResultSearch' => ['name' => $searchModel->name]], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                <?= Html::a('Разница цен ' . ($dataProvider->sort->getAttributeOrder('difference') == SORT_ASC ? '↑' : ($dataProvider->sort->getAttributeOrder('difference') == SORT_DESC ? '↓' : '')), ['index', 'sort' => ($dataProvider->sort->getAttributeOrder('difference') == SORT_ASC ? '-difference' : 'difference'), 'TradingDayResultSearch' => ['name' => $searchModel->name]], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                <?= Html::a('Разница цен в %' . ($dataProvider->sort->getAttributeOrder('differenceInPercent') == SORT_ASC ? '↑' : ($dataProvider->sort->getAttributeOrder('differenceInPercent') == SORT_DESC ? '↓' : '')), ['index', 'sort' => ($dataProvider->sort->getAttributeOrder('differenceInPercent') == SORT_ASC ? '-differenceInPercent' : 'differenceInPercent'), 'TradingDayResultSearch' => ['name' => $searchModel->name]], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
            </div>
        </div>

        <!-- ListView -->
        <div class="row justify-content-center">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'pager' => [
                    'class' => \yii\bootstrap5\LinkPager::class,
                ],
                'options' => [
                    'tag' => 'div',
                    'class' => 'list-wrapper col-xl-6 col-md-8 col-sm-12',
                ],
                'layout' => '{items}{pager}',
                'itemView' => '_share-trading-day-result',
            ]) ?>
        </div>
    <?php else: ?>
        <div class="col-12 text-center">
            <h2>Нет информации</h2>
        </div>
    <?php endif; ?>
</div>