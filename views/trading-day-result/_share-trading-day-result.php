<?php

use lib\FrontendHelper\DetailViewCopyHelper;
use src\ViewHelper\TradingDay\OldNewValueComparisonViewHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var array{
 * shareId: int,
 * shareDealId: int,
 * registerNumber: string,
 * issuerName: string,
 * name: string,
 *
 * selectedDayMaxPrice: float,
 * selectedDayMinPrice: float,
 * selectedDayPrice: float,
 * selectedDayDate: string,
 * selectedDayTotalSum: float,
 * selectedDayTotalAmount: int,
 * selectedDayTotalDealAmount: int,
 *
 * previousDayMaxPrice: float,
 * previousDayMinPrice: float,
 * previousDayPrice: float,
 * previousDayDate: string,
 * previousDayTotalSum: float,
 * previousDayTotalAmount: int,
 * previousDayTotalDealAmount: int,
 *
 * minPriceDifference: float,
 * maxPriceDifference: float,
 * difference: float,
 * } $model
 */
?>

<div class="mb-3 col-12">
    <div class="card" id=<?= $model['shareDealId'] ?>>
        <div class="card-header bg-primary text-white">
            <h2><?= Html::a(
                $model['name'],
                Url::to(['share/deal-info', 'id' => $model['shareId']]),
                [
                    'target' => '_blank',
                    'style' => ['color' => 'whitesmoke'],
                ]
            ) ?></h2>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">
                    <span>Средневзвешенная цена:</span>
                </dt>
                <dd class="col-sm-8">
                    <?php if ($model['previousDayPrice']): ?>
                        <?= DetailViewCopyHelper::renderValueColored($model['selectedDayPrice']) ?>
                        <?= OldNewValueComparisonViewHelper::render($model['selectedDayPrice'], $model['previousDayPrice']) ?>
                    <?php else: ?>
                        <?= DetailViewCopyHelper::renderValueColored($model['selectedDayPrice']) ?>
                    <?php endif; ?>
                </dd>
            </dl>
            <dl class="row">
                <dt class="col-sm-4">
                    <span>Минимальная цена:</span>
                </dt>
                <dd class="col-sm-8">
                    <?php if ($model['previousDayMinPrice']): ?>
                        <?= DetailViewCopyHelper::renderValueColored($model['selectedDayMinPrice']) ?>
                        <?= OldNewValueComparisonViewHelper::render($model['selectedDayMinPrice'], $model['previousDayMinPrice']) ?>
                    <?php else: ?>
                        <?= DetailViewCopyHelper::renderValueColored($model['selectedDayMinPrice']) ?>
                    <?php endif; ?>
                </dd>
            </dl>
            <dl class="row">
                <dt class="col-sm-4">
                    <span>Максимальная цена:</span>
                </dt>
                <dd class="col-sm-8">
                    <?php if ($model['previousDayMaxPrice']): ?>
                        <?= DetailViewCopyHelper::renderValueColored($model['selectedDayMaxPrice']) ?>
                        <?= OldNewValueComparisonViewHelper::render($model['selectedDayMaxPrice'], $model['previousDayMaxPrice']) ?>
                    <?php else: ?>
                        <?= DetailViewCopyHelper::renderValueColored($model['selectedDayMaxPrice']) ?>
                    <?php endif; ?>
                </dd>
            </dl>
            <dl class="row">
                <dt class="col-sm-4">
                    <span>Сумма сделок:</span>
                </dt>
                <dd class="col-sm-8">
                    <?php if ($model['previousDayTotalSum']): ?>
                        <?= DetailViewCopyHelper::renderValueColored($model['selectedDayTotalSum']) ?>
                        <?= OldNewValueComparisonViewHelper::render($model['selectedDayTotalSum'], $model['previousDayTotalSum']) ?>
                    <?php else: ?>
                        <?= DetailViewCopyHelper::renderValueColored($model['selectedDayTotalSum']) ?>
                    <?php endif; ?>
                </dd>
            </dl>
            <dl class="row">
                <dt class="col-sm-4">
                    <span>Кол-во купленных акций:</span>
                </dt>
                <dd class="col-sm-8">
                    <?php if ($model['previousDayTotalAmount']): ?>
                        <?= DetailViewCopyHelper::renderValueColored($model['selectedDayTotalAmount']) ?>
                        <?= OldNewValueComparisonViewHelper::render($model['selectedDayTotalAmount'], $model['previousDayTotalAmount']) ?>
                    <?php else: ?>
                        <?= DetailViewCopyHelper::renderValueColored($model['selectedDayTotalAmount']) ?>
                    <?php endif; ?>
                </dd>
            </dl>
            <dl class="row">
                <dt class="col-sm-4">
                    <span>Кол-во сделок:</span>
                </dt>
                <dd class="col-sm-8">
                    <?php if ($model['previousDayTotalDealAmount']): ?>
                        <?= DetailViewCopyHelper::renderValueColored($model['selectedDayTotalDealAmount']) ?>
                        <?= OldNewValueComparisonViewHelper::render($model['selectedDayTotalDealAmount'], $model['previousDayTotalDealAmount']) ?>
                    <?php else: ?>
                        <?= DetailViewCopyHelper::renderValueColored($model['selectedDayTotalDealAmount']) ?>
                    <?php endif; ?>
                </dd>
            </dl>
        </div>
        <div class="card-footer text-end">
            Предыдущий отчетный день: <?= Yii::$app->formatter->asDate($model['previousDayDate'], 'full') ?>
        </div>
    </div>
</div>
