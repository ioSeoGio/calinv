<?php

namespace app\widgets;

use yii\base\Widget;

class YandexMetricsWidget extends Widget
{
    public function run()
    {
        return $this->render('yandex-metrics', []);
    }
}
