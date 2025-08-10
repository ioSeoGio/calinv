<?php

use miloschuman\highcharts\Highstock;
use yii\web\JsExpression;
use miloschuman\highcharts\SeriesDataHelper;

$this->registerJs('const myCallbackFunction = function () {};');

$js = <<<MOO
    $(function () {
        var seriesOptions = [],
            seriesCounter = 0,
            names = ['MSFT', 'AAPL', 'GOOG'];

        $.each(names, function(i, name) {

            $.getJSON('https://cdn.jsdelivr.net/gh/highcharts/highcharts@v7.0.0/samples/data/'+ name.toLowerCase() +'-c.json',	function(data) {

                seriesOptions[i] = {
                    name: name,
                    data: data
                };

                // As we're loading the data asynchronously, we don't know what order it will arrive. So
                // we keep a counter and create the chart when all the data is loaded.
                seriesCounter++;

                if (seriesCounter == names.length) {
                    createChart(seriesOptions);
                }
            });
        });
    });
MOO;

$this->registerJs($js);

echo Highstock::widget([
    // The highcharts initialization statement will be wrapped in a function
    // named 'createChart' with one parameter: data.
    'callback' => 'createChart',
    'options' => [
        'rangeSelector' => [
            'selected' => 4
        ],
        'yAxis' => [
            'labels' => [
                'formatter' => new JsExpression("function () {
                    return (this.value > 0 ? ' + ' : '') + this.value + '%';
                }")
            ],
            'plotLines' => [[
                'value' => 0,
                'width' => 2,
                'color' => 'silver'
            ]]
        ],
        'plotOptions' => [
            'series' => [
                'compare' => 'percent'
            ]
        ],
        'tooltip' => [
            'pointFormat' => '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.change}%)<br/>',
            'valueDecimals' => 2
        ],
        'series' => new JsExpression('data'), // Here we use the callback parameter, data
    ]
]);

?>