<?php

/** @var yii\web\View $this */
/** @var Issuer $model */
/** @var IssuerEventSearchForm $searchForm */
/** @var ActiveDataProvider $eventDataProvider */
/** @var ActiveDataProvider $importantEventDataProvider */

use lib\FrontendHelper\DetailViewCopyHelper;
use lib\FrontendHelper\Issuer\Share\IssuerShareFullnessStateIconPrinter;
use lib\FrontendHelper\Issuer\Share\IssuerShareInfoModeratedIconPrinter;
use src\Action\Issuer\Event\IssuerEventSearchForm;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\IssuerEvent\IssuerEvent;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->params['breadcrumbs.homeLink'] = false;
$this->params['breadcrumbs'][] = ['label' => 'Эмитенты', 'url' => ['issuer/index']];
$this->params['breadcrumbs'][] = $model->name;
$this->title = $model->name;
?>

<?= $this->render('@views/_parts/issuer_tabs', [
    'model' => $model,
]); ?>
<div class="issuer-view">
    <?= GridView::widget([
        'dataProvider' => $importantEventDataProvider,
        'columns' => [
            [
                'label' => 'Важное событие за последние 2 года',
                'attribute' => 'eventName',
            ],
            '_eventDate:datetime',
        ],
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => '',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return Html::a('Обновить общую информацию', ['update-issuer-info', 'id' => $model->id], ['class' => 'btn btn-success']);
                }
            ],
            [
                'label' => 'Наименование',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return
                        IssuerShareFullnessStateIconPrinter::print($model)
                        . IssuerShareInfoModeratedIconPrinter::print($model)
                        . DetailViewCopyHelper::render($model, 'name');
                }
            ],
            '_legalStatus',
            [
                'attribute' => '_pid',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return DetailViewCopyHelper::render($model, '_pid');
                }
            ],
            [
                'label' => 'Данные о акциях проверены?',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    if ($model->dateShareInfoModerated) {
                        return Html::tag(
                            'span',
                            Yii::$app->formatter->asBoolean((bool) $model->dateShareInfoModerated)
                                . ', '
                                . Yii::$app->formatter->asRelativeTime($model->dateShareInfoModerated),
                            ['class' => 'btn btn-success']
                        )
                            . '<br>'
                            . Html::a("Пометить ненадежными", ['share/toggle-moderation', 'issuerId' => $model->id], ['class' => 'btn btn-danger']);
                    }

                    return Html::tag(
                        'span',
                        Yii::$app->formatter->asBoolean((bool) $model->dateShareInfoModerated),
                        ['class' => 'btn btn-danger']
                    )
                        . '<br>'
                        . Html::a("Пометить надежными", ['share/toggle-moderation', 'issuerId' => $model->id], ['class' => 'btn btn-success']);
                },
            ],
            [
                'label' => '',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return Html::a(
                            "Обновить акции",
                            ['update-issuer-info', 'id' => $model->id],
                            ['class' => 'btn btn-success']
                        )
                        . '<br>'
                        . Html::a("Активные акции ({$model->getActiveShares()->count()})", ['/share', 'ShareSearchForm' => [
                            'issuerId' =>  $model->id,
                        ]], ['target' => '_blank', 'class' => 'btn btn-primary'])
                        . '<br>'
                        . Html::a("Акции ({$model->getShares()->count()})", ['/share/all-shares', 'ShareSearchForm' => [
                            'issuerId' =>  $model->id,
                        ]], ['target' => '_blank', 'class' => 'btn btn-primary']);
                }
            ],
            [
                'label' => 'BIK рейтинг',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    $bikRating = $model->businessReputationInfo;

                    if ($bikRating !== null) {
                        return Html::a($bikRating->rating->value, $bikRating->pressReleaseLink, ['target' => '_blank']);
                    }

                    return null;
                }
            ],

            [
                'label' => '',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return Html::a('Обновить адрес', ['renew-address', 'id' => $model->id], ['class' => 'btn btn-success']);
                }
            ],
            'addressInfo.fullAddress',
            'addressInfo.email',
            [
                'attribute' => 'addressInfo.site',
                'format' => 'raw',
                'value' =>  function (Issuer $model) {
                    $siteUrl = $model->addressInfo?->site;

                    return $siteUrl ? Html::a($siteUrl, $siteUrl, ['target' => '_blank']) : null;
                }
            ],
            'addressInfo.phones',

            [
                'label' => '',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return Html::a('Обновить вид деятельности', ['renew-type-of-activity', 'id' => $model->id], ['class' => 'btn btn-success']);
                }
            ],
            'typeOfActivity.name',
            'typeOfActivity.code',
        ],
    ]) ?>
    <hr>

    <div>
        Время последнего обновления: <?= Yii::$app->formatter->asDatetime(IssuerEvent::getLastUpdateSessionDate(['_pid' => $model->_pid])) ?>
        <div>
            <?= Html::a('Обновить', ['renew-issuer-events', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $eventDataProvider,
        'filterModel' => $searchForm,
        'columns' => [
            'eventName',
            '_eventDate:datetime',
        ],
    ]) ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
    });
');
