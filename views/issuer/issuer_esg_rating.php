<?php

/** @var yii\web\View $this */
/** @var ActiveDataProvider $dataProvider */
/** @var EsgRatingInfoSearch $searchForm */

use src\Action\Issuer\Rating\EsgRatingInfoSearch;
use src\Entity\Issuer\EsgRating\EsgRatingInfo;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'ESG Рейтинг BIK';
?>
<?= $this->render('tabs', []); ?>
<div>
    Время последнего обновления: <?= Yii::$app->formatter->asDatetime(EsgRatingInfo::getLastUpdateSessionDate()) ?>
    <div>
        <?= Html::a('Обновить', ['renew-esg-rating'], ['class' => 'btn btn-success']) ?>
    </div>
</div>
<div class="esg-rating-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchForm,
        'columns' => [
            [
                'label' => 'эмитент',
                'attribute' => 'issuerName',
            ],
            '_pid',
            '_rating',
            '_lastUpdateDate',
            'pressReleaseLink:url',
        ],
    ]) ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
    });
');
