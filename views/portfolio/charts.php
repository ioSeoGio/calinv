<?php

use app\widgets\DynamicPieChartWidget;
use lib\FrontendHelper\SimpleNumberFormatter;
use src\Entity\PersonalShare\PersonalShare;
use yii\helpers\ArrayHelper;

/** @var \yii\data\ActiveDataProvider $dataProvider */

?>
<?= $this->render('tabs', []); ?>
<div class="row justify-content-center">

    <?php $data = ArrayHelper::map(
        $dataProvider->query->all(),
        'id',
        function (PersonalShare $personalShare) {
            return [
                'name' => $personalShare->share->getFormattedNameWithIssuer(),
                'y' => round($personalShare->getTotalCurrentPriceSum(), 2),
                'z' => round($personalShare->getTotalProfitSum(), 2),
            ];
        },
    );
    $data = array_values($data); ?>
    <div class="col-12 col-xxl-6">
        <?= DynamicPieChartWidget::widget([
            'data' => $data,
            'title' => 'Доли портфеля по текущей цене акций в рублях. Чем шире кусок - тем больше доля по текущей цене. Чем жирнее - тем больше доля прибыли',
            'pointFormat' => '
                <span style="color:{point.color}"></span> <b>{point.name}</b><br/>Сумма в портфеле по текущей цене акции: <b>{point.y} р.</b><br/>Всего прибыли: <b>{point.z} р.</b><br/>
            ',
        ]); ?>
    </div>

    <?php $data = ArrayHelper::map(
        $dataProvider->query->all(),
        'id',
        function (PersonalShare $personalShare) {
            return [
                'name' => $personalShare->share->getFormattedNameWithIssuer(),
                'y' => round($personalShare->getTotalBoughtSum(), 2),
                'z' => round($personalShare->getTotalProfitSum(), 2),
            ];
        },
    );
    $data = array_values($data); ?>
    <div class="col-12 col-xxl-6">
        <?= DynamicPieChartWidget::widget([
            'data' => $data,
            'title' => 'Доли портфеля по закупочной цене акций в рублях. Чем шире кусок - тем больше доля по закупочной цене. Чем жирнее - тем больше доля прибыли',
            'pointFormat' => '
                <span style="color:{point.color}"></span> <b>{point.name}</b><br/>Сумма в портфеле по закупочной цене акции: <b>{point.y} р.</b><br/>Всего прибыли: <b>{point.z} р.</b><br/>
            ',
        ]); ?>
    </div>

    <?php $data = ArrayHelper::map(
        $dataProvider->query
            ->andWhere(['IS NOT', 'share.currentPrice', null])
            ->andWhere('share."currentPrice" >= "buyPrice"')
            ->all(),
        'id',
        function (PersonalShare $personalShare) {
            return [
                'name' => $personalShare->share->getFormattedNameWithIssuer(),
                'y' => round($personalShare->getTotalProfitSum(), 2),
                'z' => 1,
            ];
        },
    );
    $data = array_values($data);
    ?>

    <hr>
    <div class="col-12 col-xxl-6">
        <?= DynamicPieChartWidget::widget([
            'data' => $data,
            'title' => 'Доли прибыли в портфеле по акциям в рублях. Убыточные в графике не отображаются <br>Всего '
                    . SimpleNumberFormatter::toView(Yii::$app->user->identity->getTotalProfit())
                    . ' р. прибыли',
            'pointFormat' => '
                <span style="color:{point.color}"></span> <b>{point.name}</b><br/>Прибыль: <b>{point.y} р.</b><br/>
            ',
        ]); ?>
    </div>
</div>
