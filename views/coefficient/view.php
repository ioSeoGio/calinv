<?php

/** @var yii\web\View $this */
/** @var Issuer $model */
/** @var IssuerEventSearchForm $searchForm */
/** @var ActiveDataProvider $eventDataProvider */
/** @var ActiveDataProvider $importantEventDataProvider */

use lib\MetaTag\MetaTagManager;
use src\Action\Issuer\Event\IssuerEventSearchForm;
use src\Entity\Issuer\Issuer;
use yii\data\ActiveDataProvider;

MetaTagManager::registerIssuerTags($this, $model);

$this->params['breadcrumbs.homeLink'] = false;
$this->params['breadcrumbs'][] = ['label' => 'Эмитенты', 'url' => ['issuer/index']];
$this->params['breadcrumbs'][] = $model->name;
$this->title = $model->name . " УНП $model->_pid";;
?>

<?= $this->render('@views/_parts/issuer_tabs', [
    'model' => $model,
]); ?>
<div class="issuer-view">
    <?= $this->render('_coefficient-part', [
        'model' => $model,
        'capitalization' => null,
    ]); ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
    });
');
