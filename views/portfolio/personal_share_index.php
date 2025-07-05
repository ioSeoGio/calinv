<?php

use src\Entity\Issuer\Issuer;
use src\Entity\PersonalShare\PersonalShare;
use src\Entity\Share\Share;
use yii\base\Model;
use yii\bootstrap5\Html;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\web\View;

/** @var ArrayDataProvider $personalShareDataProvider */
/** @var Model $personalShareSearchForm */
/** @var Model $personalShareCreateForm */
/** @var View $this */

$queryParamUserId = Yii::$app->request->queryParams['userId'] ?? null;
?>
<?= $this->render('tabs', []); ?>
<?php if ($queryParamUserId === null || $queryParamUserId === Yii::$app->user->id) {
    echo $this->render('personal_share_creation', [
        'personalShareCreateForm' => $personalShareCreateForm,
    ]);
} ?>
<?= $sharesContent = GridView::widget([
    'dataProvider' => $personalShareDataProvider,
//    'filterModel' => $personalShareSearchForm,
    'columns' => [
        [
            'attribute' => 'share.registerNumber',
        ],
        [
            'label' => 'эмитент',
            'attribute' => 'share.issuer.name',
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
                return $model->share->currentPrice ? Html::tag(
                    name: 'span',
                    content: round($model->share->currentPrice, 2) . ' руб.',
                    options: ['class' => 'text-primary']
                ) : null;
            }
        ],
        [
            'label' => 'дата покупки',
            'attribute' => 'boughtAt',
        ],
        [
            'label' => 'объем выпуска',
            'attribute' => 'share.volumeIssued',
            'value' => function (PersonalShare $model) {
                return $model->share->totalIssuedAmount . ' шт.';
            }
        ],
    ],
]) ?>
