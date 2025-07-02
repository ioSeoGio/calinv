<?php

/** @var yii\web\View $this */
/** @var Issuer $model */
/** @var IssuerEventSearchForm $searchForm */
/** @var ActiveDataProvider $eventDataProvider */

use src\Action\Issuer\Event\IssuerEventSearchForm;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\IssuerEvent\IssuerEvent;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

$this->params['breadcrumbs.homeLink'] = false;
$this->params['breadcrumbs'][] = ['label' => 'Эмитенты', 'url' => ['issuer/index']];
$this->params['breadcrumbs'][] = Html::encode($model->name);
$this->title = Html::encode($model->name);
?>
<div class="issuer-view">
    <?= \yii\widgets\DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Наименование',
                'attribute' => 'name',
            ],
            '_legalStatus',
            '_pid',
            [
                'label' => 'BIK рейтинг',
                'value' => function (Issuer $model) {
                    return $model->businessReputationInfo?->rating->value;
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
