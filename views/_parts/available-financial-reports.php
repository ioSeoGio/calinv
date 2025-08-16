<?php

/** @var \yii\data\ActiveDataProvider $dataProvider */

/** @var Issuer $issuer */

use src\Entity\Issuer\Issuer;
use yii\helpers\Html;
use yii\helpers\Url;

$dataProvider->pagination = false;
?>

<div class="card mt-3" style="min-width: 250px">
    <div class="card-body">
        <ul class="list-group list-group-horizontal-sm">
            <?php foreach ($dataProvider->getModels() as $model): ?>
                <li class="list-group-item">
                    <?= \lib\FrontendHelper\DetailViewCopyHelper::renderValueColored($model->_year) ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?= Html::a(
            'Обновить доступные отчеты Legat (платно)',
            Url::to(['/accounting-balance/renew-available-financial-reports', 'issuerId' => $issuer->id]),
            ['class' => 'btn btn-danger mt-3']
        ) ?>
    </div>
</div>