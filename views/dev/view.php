<?php
use yii\helpers\Html;
use yii\helpers\Url;

echo Html::a('download', Url::to(['dev/custom-csv-export']), []);