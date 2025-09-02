<?php

/** @var yii\web\View $this */
/** @var ActiveDataProvider $dataProvider */
/** @var EsgRatingInfoSearch $searchForm */

use lib\FrontendHelper\DetailViewCopyHelper;
use src\Action\Issuer\Rating\EsgRatingInfoSearch;
use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfo;
use src\Entity\Issuer\CreditRating\CreditRatingInfo;
use src\Entity\Issuer\EsgRating\EsgRatingInfo;
use src\Entity\Issuer\Issuer;
use src\Entity\User\UserRole;
use src\ViewHelper\IssuerRating\IssuerBikRatingViewHelper;
use src\ViewHelper\Shit\RatingSelectIssuerDropdown;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

$this->registerJsVar('ajaxRatingChangeIssuerUrl', Url::to(['/issuer-rating/ajax-credit-rating-change-issuer']));
$this->registerJsFile('@web/js/rating-select-issuer-dropdown.js');
$this->title = 'Кредитный рейтинг BIK';
?>
<?= $this->render('@views/issuer/tabs', []); ?>
<?php if (Yii::$app->user->can(UserRole::admin->value)) : ?>
<div>
    Время последнего обновления: <?= Yii::$app->formatter->asDatetime(EsgRatingInfo::getLastUpdateSessionDate()) ?>
    <div>
        <?= Html::a('Обновить', ['renew-credit-rating'], ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?php endif; ?>

<?php
if (Yii::$app->user->can(UserRole::admin->value)) {
    $issuers = Issuer::find()->all();
}
?>

<div class="esg-rating-index">
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
                'value' => function (CreditRatingInfo $model) use ($issuers) {
                    if (Yii::$app->user->can(UserRole::admin->value)) {
                        return RatingSelectIssuerDropdown::render($model, $issuers);
                    }

                    return $model->issuer
                        ? Html::a($model->issuer->name, Url::to(['issuer/view', 'id' => $model->issuer->id]))
                        : \lib\FrontendHelper\NullableValue::printNull();
                },
                'options' => ['style' => 'min-width: 200px'],
            ],
            [
                'attribute' => '_forecast',
                'format' => 'raw',
                'value' => function (CreditRatingInfo $model) {
                    return IssuerBikRatingViewHelper::renderCreditRatingForecast($model, false);
                }
            ],
            [
                'attribute' => '_rating',
                'format' => 'raw',
                'value' => function (CreditRatingInfo $model) {
                    return IssuerBikRatingViewHelper::renderCreditRating($model, false);
                }
            ],
            '_lastUpdateDate:date',
            '_assignmentDate:date',
            [
                'attribute' => 'pressReleaseLink',
                'format' => 'raw',
                'value' => function (CreditRatingInfo $model) {
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
