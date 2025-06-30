<?php

/** @var yii\web\View $this */
/** @var ActiveDataProvider $dataProvider */
/** @var \src\Action\Issuer\Rating\BusinessReputationInfoSearch $searchForm */

use yii\data\ActiveDataProvider;
use yii\grid\GridView;

$this->title = 'Рейтинг деловой репутации BIK';
?>
<?= $this->render('tabs', []); ?>
<div class="calculator-index">
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
