<?php

use app\models\FinancialInstrument\Share;
use app\models\IssuerRating\IssuerRating;
use app\models\Portfolio\PersonalShare;
use src\IssuerRatingCalculator\Static\YieldToMaturityCalculator;
use yii\base\Model;
use yii\bootstrap5\Html;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var ArrayDataProvider $personalShareDataProvider */
/** @var Model $personalShareSearchForm */
/** @var Model $personalShareForm */

?>
<?= $this->render('tabs', []); ?>
<?php if (!isset(Yii::$app->request->queryParams['userId'])) {
    echo $this->render('personal_share_creation', [
        'personalShareForm' => $personalShareForm,
    ]);
} ?>
<?= $sharesContent = GridView::widget([
    'dataProvider' => $personalShareDataProvider,
    'filterModel' => $personalShareSearchForm,
    'columns' => [
        [
            'label' => 'имя выпуска',
            'attribute' => 'share.name',
            'filter' => Html::activeDropDownList(
                $personalShareSearchForm,
                'shareId',
                array_merge(
                    ['All' => 'Все'],
                    ArrayHelper::map(
                        Share::find()->all(),
                        fn (Share $model) => (string) $model->_id,
                        fn (Share $model) => $model->issuerRating?->issuer .' - '.$model->name,
                ),
            ), ['class' => 'form-control']),
        ],
        [
            'label' => 'эмитент',
            'attribute' => 'share.issuerRating.issuer',
            'filter' => Html::activeDropDownList(
                $personalShareSearchForm,
                'issuerId',
                array_merge(
                    ['All' => 'Все'],
                    ArrayHelper::map(
                        IssuerRating::find()->all(),
                        fn (IssuerRating $issuerRating) => (string) $issuerRating->_id,
                        'issuer'
                ),
            ), ['class' => 'form-control']),
        ],
        [
            'label' => 'прибыль, всего',
            'format' => 'raw',
            'value' => function (PersonalShare $model) {
                $value = ($model->share->currentPrice - $model->buyPrice) / $model->buyPrice * 100;

                 return Html::tag(
                    name: 'span',
                    content: round($value, 1) . ' %',
                    options: ['class' => $value > 0 ? 'text-success' : 'text-danger']
                );
            }
        ],
        [
            'label' => 'номинал',
            'attribute' => 'share.denomination',
            'format' => 'raw',
            'value' => function (PersonalShare $model) {
                return Html::tag(
                    name: 'span',
                    content: round($model->share->denomination, 2) . ' руб.',
                    options: ['class' => $model->share->denomination >= $model->share->currentPrice ? 'text-success' : 'text-danger']
                );
            }
        ],
        [
            'label' => 'цена покупки',
            'attribute' => 'buyPrice',
            'format' => 'raw',
            'value' => function (PersonalShare $model) {
                return Html::tag(
                    name: 'span',
                    content: round($model->buyPrice, 2) . ' руб.',
                    options: ['class' => $model->buyPrice <= $model->share->currentPrice  ? 'text-success' : 'text-danger']
                );
            }
        ],
        [
            'label' => 'текущая цена',
            'attribute' => 'share.currentPrice',
            'format' => 'raw',
            'value' => function (PersonalShare $model) {
                return Html::tag(
                    name: 'span',
                    content: round($model->share->currentPrice, 2) . ' руб.',
                    options: ['class' => 'text-primary']
                );
            }
        ],
        [
            'label' => 'дата покупки',
            'attribute' => 'buyDate',
        ],
        [
            'label' => 'объем выпуска',
            'attribute' => 'share.volumeIssued',
            'value' => function (PersonalShare $model) {
                return $model->share->volumeIssued . ' шт.';
            }
        ],
    ],
]) ?>
