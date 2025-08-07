<?php

/** @var yii\web\View $this */
/** @var ActiveDataProvider $dataProvider */
/** @var EsgRatingInfoSearch $searchForm */

use lib\FrontendHelper\DetailViewCopyHelper;
use src\Action\Issuer\Rating\EsgRatingInfoSearch;
use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfo;
use src\Entity\Issuer\CreditRating\CreditRatingInfo;
use src\Entity\Issuer\EsgRating\EsgRatingInfo;
use src\Entity\User\UserRole;
use src\ViewHelper\IssuerRating\IssuerBikRatingViewHelper;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\StringHelper;

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
