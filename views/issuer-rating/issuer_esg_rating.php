<?php

/** @var yii\web\View $this */
/** @var ActiveDataProvider $dataProvider */
/** @var EsgRatingInfoSearch $searchForm */

use lib\FrontendHelper\DetailViewCopyHelper;
use src\Action\Issuer\Rating\EsgRatingInfoSearch;
use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfo;
use src\Entity\Issuer\EsgRating\EsgRatingInfo;
use src\Entity\Issuer\Issuer;
use src\Entity\User\UserRole;
use src\ViewHelper\IssuerRating\IssuerBikRatingViewHelper;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

$this->registerJsVar('ajaxRatingChangeIssuerUrl', Url::to(['/issuer-rating/ajax-esg-rating-change-issuer']));
$this->registerJsFile('@web/js/rating-select-issuer-dropdown.js');
$this->title = 'ESG Рейтинг BIK';
?>
<?= $this->render('@views/issuer/tabs', []); ?>
<?php if (Yii::$app->user->can(UserRole::admin->value)) : ?>
<div>
    Время последнего обновления: <?= Yii::$app->formatter->asDatetime(EsgRatingInfo::getLastUpdateSessionDate()) ?>
    <div>
        <?= Html::a('Обновить', ['renew-esg-rating'], ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?php endif; ?>

<?php
$issuers = Yii::$app->user->can(UserRole::admin->value) ? Issuer::find()->all() : [];
?>

<div class="esg-rating-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'class' => \yii\bootstrap5\LinkPager::class,
        ],
        'filterModel' => $searchForm,
        'columns' => [
            [
                'attribute' => 'issuerName',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Поиск по эмитенту...',
                ],
            ],
            [
                'label' => 'Эмитент',
                'format' => 'raw',
                'value' => function (EsgRatingInfo $model) use ($issuers) {
                    if (Yii::$app->user->can(UserRole::admin->value)) {
                        return \src\ViewHelper\Shit\RatingSelectIssuerDropdown::render($model, $issuers);
                    }

                    return $model->issuer
                        ? Html::a($model->issuer->name, Url::to(['issuer/view', 'unp' => $model->issuer->_pid]))
                        : \lib\FrontendHelper\NullableValue::printNull();
                },
                'options' => ['style' => 'min-width: 200px'],
            ],
            [
                'attribute' => '_rating',
                'format' => 'raw',
                'value' => function (EsgRatingInfo $model) {
                    return IssuerBikRatingViewHelper::renderEsgRating($model, false);
                }
            ],
            '_lastUpdateDate:date',
            '_assignmentDate:date',
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
