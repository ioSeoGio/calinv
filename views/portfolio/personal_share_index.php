<?php

use app\widgets\GuardedActionColumn;
use lib\FrontendHelper\DetailViewCopyHelper;
use lib\FrontendHelper\SimpleNumberFormatter;
use src\Entity\PersonalShare\PersonalShare;
use src\Entity\User\UserRole;
use src\ViewHelper\Tools\Badge;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/** @var ArrayDataProvider $personalShareDataProvider */
/** @var Model $personalShareSearchForm */
/** @var Model $personalShareCreateForm */
/** @var View $this */

$this->title = 'Мой портфель';
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
                $issuer = $personalShare->share->issuer;
                return Html::a($issuer->name, Url::to(['/issuer/view', 'id' => $issuer->id]))
                    . ' - '
                    . $personalShare->share->getFormattedName();
            },
        ],
        [
            'attribute' => 'amount',
        ],
        [
            'label' => 'прибыль %',
            'format' => 'raw',
            'value' => function (PersonalShare $model) {
                $value = ($model->share->currentPrice - $model->buyPrice) / $model->buyPrice * 100;


                if ($value === 0) {
                    return Badge::neutral(SimpleNumberFormatter::toView($value, 1) . '%');
                }

                return $value > 0
                    ? Badge::success(SimpleNumberFormatter::toView($value, 1) . '%')
                    : Badge::danger(SimpleNumberFormatter::toView($value, 1) . '%');
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
        [
            'class' => GuardedActionColumn::class,
            'buttonsConfig' => [
                'delete' => [
                    'icon' => 'bi bi-trash',
                    'url' => function (PersonalShare $model) {
                        return Url::to(['/personal-share/delete', 'id' => $model->id]);
                    },
                    'isVisible' => function (PersonalShare $model, $key) {
                        return $model->user_id === Yii::$app->user->id || Yii::$app->user->can(UserRole::admin->value);
                    },
                    'options' => [
                        'title' => 'Удалить запись',
                    ],
                ],
            ],
        ],
    ],
]) ?>
