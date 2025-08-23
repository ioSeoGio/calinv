<?php

use app\widgets\DynamicPieChartWidget;
use lib\FrontendHelper\SimpleNumberFormatter;
use src\Entity\PersonalShare\PersonalShare;
use yii\helpers\ArrayHelper;

/** @var \yii\data\ActiveDataProvider $dataProvider */

echo $this->render('tabs', []);

$data = ArrayHelper::map(
        $dataProvider->query->all(),
        'id',
        function (PersonalShare $personalShare) {
            return [
                'name' => $personalShare->share->getFormattedNameWithIssuer(),
                'y' => $personalShare->getTotalCurrentPriceSum(),
                'z' => $personalShare->getTotalProfitSum(),
            ];
        },
    );
$data = array_values($data);
echo DynamicPieChartWidget::widget([
    'data' => $data,
    'title' => 'Доли портфеля в рублях',
    'pointFormat' => '
        <span style="color:{point.color}"></span> <b>{point.name}</b><br/>По текущей цене: <b>{point.y} р.</b><br/>По прибыли: <b>{point.z} р.</b><br/>
    ',
]);

$data = ArrayHelper::map(
        $dataProvider->query
            ->andWhere(['IS NOT', 'share.currentPrice', null])
            ->andWhere('share."currentPrice" >= "buyPrice"')
            ->all(),
        'id',
        function (PersonalShare $personalShare) {
            return [
                'name' => $personalShare->share->getFormattedNameWithIssuer(),
                'y' => $personalShare->getTotalProfitSum(),
                'z' => 1,
            ];
        },
    );
$data = array_values($data);
echo "<hr>";
echo DynamicPieChartWidget::widget([
    'data' => $data,
    'title' => 'Доли прибыли в портфеле по акциям в рублях. Убыточные в графике не отображаются <br>Всего '
        . SimpleNumberFormatter::toView(Yii::$app->user->identity->getTotalProfit())
        . ' р. прибыли',
    'pointFormat' => '
        <span style="color:{point.color}"></span> <b>{point.name}</b><br/>Прибыль: <b>{point.y} р.</b><br/>
    ',
]);