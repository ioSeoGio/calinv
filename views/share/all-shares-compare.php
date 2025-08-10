<?php

use miloschuman\highcharts\Highstock;
use src\Entity\Share\Share;
use yii\helpers\Url;
use yii\web\JsExpression;
use miloschuman\highcharts\SeriesDataHelper;
//            $.getJSON('https://cdn.jsdelivr.net/gh/highcharts/highcharts@v7.0.0/samples/data/'+ name.toLowerCase() +'-c.json',	function(data) {
//            names = ['MSFT', 'AAPL', 'GOOG'];

$this->registerJsVar('getShareDealInfoUrl', Url::to(['/share/test-data']));
$this->registerJsVar('shareIds', Share::getShareIdsWithDeals());
$js = <<<MOO
    $(function () {
        var seriesOptions = [],
            seriesCounter = 0;

        $.each(shareIds, function(i, shareId) {

            $.getJSON(getShareDealInfoUrl + '?shareId=' + shareId,	function(data) {

                seriesOptions[i] = {
                    name: data.shareName,
                    data: data.values
                };

                // As we're loading the data asynchronously, we don't know what order it will arrive. So
                // we keep a counter and create the chart when all the data is loaded.
                seriesCounter++;

                if (seriesCounter == shareIds.length) {
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