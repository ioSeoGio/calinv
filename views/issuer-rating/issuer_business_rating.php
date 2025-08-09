<?php

/** @var yii\web\View $this */
/** @var ActiveDataProvider $dataProvider */
/** @var BusinessReputationInfoSearch $searchForm */

use kartik\select2\Select2;
use lib\FrontendHelper\DetailViewCopyHelper;
use src\Action\Issuer\Rating\BusinessReputationInfoSearch;
use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfo;
use src\Entity\Issuer\BusinessReputationRating\IssuerBusinessReputation;
use src\Entity\Issuer\Issuer;
use src\Entity\User\UserRole;
use src\ViewHelper\IssuerRating\IssuerBikRatingViewHelper;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

$this->registerJsVar('ajaxRatingChangeIssuerUrl', Url::to(['/issuer-rating/ajax-business-rating-change-issuer']));
$this->registerJsFile('@web/js/rating-select-issuer-dropdown.js');
$this->title = 'Рейтинг деловой репутации BIK';
?>
<?= $this->render('@views/issuer/tabs', []); ?>
<?php if (Yii::$app->user->can(UserRole::admin->value)) : ?>
<div>
    Время последнего обновления: <?= Yii::$app->formatter->asDatetime(BusinessReputationInfo::getLastUpdateSessionDate()) ?>
    <div>
        <?= Html::a('Обновить', ['renew-business-rating'], ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?php endif; ?>
<div class="business-rating-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'class' => \yii\bootstrap5\LinkPager::class,
        ],
        'filterModel' => $searchForm,
        'columns' => [
            'issuerName',
            [
                'label' => 'Эмитент',
                'format' => 'raw',
                'value' => function (BusinessReputationInfo $model) {
                    if (Yii::$app->user->can(UserRole::admin->value)) {
                        return \src\ViewHelper\Shit\RatingSelectIssuerDropdown::render($model);
                    }

                    return $model->issuer
                        ? Html::a($model->issuer->name, Url::to(['issuer/view', 'id' => $model->issuer->id]))
                        : \lib\FrontendHelper\NullableValue::printNull();
                },
            ],
            [
                'attribute' => '_pid',
                'format' => 'raw',
                'value' => function (BusinessReputationInfo $model) {
                    return DetailViewCopyHelper::renderValueColored($model->_pid);
                }
            ],
            [
                'attribute' => '_rating',
                'format' => 'raw',
                'value' => function (BusinessReputationInfo $model) {
                    return IssuerBikRatingViewHelper::renderBusinessReputation($model, false);
                }
            ],
            '_lastUpdateDate:date',
            [
                'attribute' => 'pressReleaseLink',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(StringHelper::truncate($model->pressReleaseLink, 55), $model->pressReleaseLink, ['target' => '_blank']);
                }
            ],
        ],
    ]) ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
    });
');
