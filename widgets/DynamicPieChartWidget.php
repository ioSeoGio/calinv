<?php
namespace app\widgets;

use app\assets\DynamicPieChartAsset;
use yii\base\Widget;
use yii\helpers\Json;

class DynamicPieChartWidget extends Widget
{
    public string $title = 'title';
    public string $pointFormat = '<span style="color:{point.color}"></span> <b>{point.name}</b><br/>yLabel: <b>{point.y}</b><br/>zLabel: <b>{point.z}</b><br/>';
    public string $containerId = 'population-density-container';
    public array $data = [
        ['name' => 'Spain', 'y' => 505992, 'z' => 95],
        ['name' => 'France', 'y' => 551695, 'z' => 118],
        ['name' => 'Poland', 'y' => 312679, 'z' => 131],
        ['name' => 'Czech Republic', 'y' => 78865, 'z' => 136],
        ['name' => 'Italy', 'y' => 301336, 'z' => 198],
        ['name' => 'Switzerland', 'y' => 41284, 'z' => 224],
        ['name' => 'Germany', 'y' => 357114, 'z' => 238],
    ];

    public function run()
    {
        DynamicPieChartAsset::register($this->getView());
        $jsConfig = [
            'data' => $this->data,
            'title' => $this->title,
            'pointFormat' => $this->pointFormat,
        ];
        $this->getView()->registerJs("var populationDensityConfig = " . Json::encode($jsConfig) . ";", \yii\web\View::POS_HEAD);

        return $this->render('dynamic-pie-chart', [
            'containerId' => $this->containerId,
        ]);
    }
}
