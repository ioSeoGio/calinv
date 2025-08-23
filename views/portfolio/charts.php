<?php

use app\widgets\DynamicPieChartWidget;
use src\Entity\PersonalShare\PersonalShare;
use yii\helpers\ArrayHelper;

/** @var \yii\data\ActiveDataProvider $dataProvider */

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

echo $this->render('tabs', []);

echo DynamicPieChartWidget::widget([
    'data' => $data,
    'title' => 'Доли портфеля в рублях',
    'pointFormat' => '
        <span style="color:{point.color}"></span> <b>{point.name}</b><br/>По закупочной цене: <b>{point.y} р.</b><br/>По текущей цене: <b>{point.z} р.</b><br/>
    ',
]);