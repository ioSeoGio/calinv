<?php

namespace app\assets;

use yii\web\AssetBundle;

class DynamicPieChartAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/dynamic-pie-chart.css',
    ];

    public $js = [
        'js/highcharts/highcharts.js',
        'js/highcharts/variable-pie.js',
        'js/highcharts/exporting.js',
        'js/highcharts/export-data.js',
        'js/highcharts/accessibility.js',
        'js/highcharts/adaptive.js',
        'js/dynamic-pie-chart.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
