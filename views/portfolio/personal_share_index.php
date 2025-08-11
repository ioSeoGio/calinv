<?php

use lib\FrontendHelper\DetailViewCopyHelper;
use src\Entity\PersonalShare\PersonalShare;
use src\ViewHelper\Tools\Badge;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
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
    'pager' => [
        'class' => \yii\bootstrap5\LinkPager::class,
    ],
//    'filterModel' => $personalShareSearchForm,
    'columns' => [
        [
            'attribute' => 'share.registerNumber',
            'format' => 'raw',
            'value' => function (PersonalShare $personalShare) {
                return DetailViewCopyHelper::renderValueColored($personalShare->share->registerNumber);
            },
        ],
        [
            'attribute' => 'share.issuer.name',
            'format' => 'raw',
            'value' => function (PersonalShare $personalShare) {
                return DetailViewCopyHelper::renderValueColored($personalShare->share->getFormattedNameWithIssuer());
            },
        ],
        [
            'label' => 'прибыль %',
            'format' => 'raw',
            'value' => function (PersonalShare $model) {
                $value = ($model->share->currentPrice - $model->buyPrice) / $model->buyPrice * 100;


                if ($value === 0) {
                    return Badge::neutral($value . '%');
                }

                return $value > 0 ? Badge::success($value . '%') : Badge::danger($value . '%');
            }
        ],
        [
            'label' => 'номинал',
            'attribute' => 'share.denomination',
            'format' => 'raw',
            'value' => function (PersonalShare $model) {
                return Badge::neutral($model->share->denomination . ' р.');
            }
        ],
        [
            'label' => 'цена покупки',
            'attribute' => 'buyPrice',
            'format' => 'raw',
            'value' => function (PersonalShare $model) {
                return $model->buyPrice <= $model->share->currentPrice
                    ? Badge::success($model->buyPrice . ' р.')
                    : Badge::danger($model->buyPrice . ' р.');
            }
        ],
        [
            'label' => 'текущая цена',
            'attribute' => 'share.currentPrice',
            'format' => 'raw',
            'value' => function (PersonalShare $model) {
                return Badge::neutral($model->share->currentPrice . ' р.');
            }
        ],
        [
            'label' => 'дата покупки',
            'attribute' => 'boughtAt',
        ],
    ],
]) ?>
