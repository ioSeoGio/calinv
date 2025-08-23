<?php

use app\widgets\DynamicPieChartWidget;
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
                'y' => $personalShare->getTotalBoughtSum(),
                'z' => $personalShare->getTotalCurrentPriceSum(),
            ];
        },
    );
$data = array_values($data);
echo DynamicPieChartWidget::widget([
    'data' => $data,
    'title' => 'Доли портфеля в рублях. Ширина куска - доля по закупочной цене. Толщина куска - доля по текущей цене акции.',
    'pointFormat' => '
        <span style="color:{point.color}"></span> <b>{point.name}</b><br/>По закупочной цене: <b>{point.y} р.</b><br/>По текущей цене: <b>{point.z} р.</b><br/>
    ',
]);

$data = ArrayHelper::map(
        PersonalShare::find()->joinWith(['share.issuer'])
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
echo DynamicPieChartWidget::widget([
    'data' => $data,
    'title' => 'Доли прибыли в портфеле по акциям в рублях',
    'pointFormat' => '
        <span style="color:{point.color}"></span> <b>{point.name}</b><br/>Прибыль: <b>{point.y} р.</b><br/>
    ',
]);