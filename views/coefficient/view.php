<?php

/** @var yii\web\View $this */
/** @var Issuer $model */
/** @var IssuerEventSearchForm $searchForm */
/** @var ActiveDataProvider $eventDataProvider */
/** @var ActiveDataProvider $importantEventDataProvider */

use lib\Helper\DetailViewCopyHelper;
use src\Action\Issuer\Event\IssuerEventSearchForm;
use src\Entity\Issuer\Issuer;
use yii\data\ActiveDataProvider;
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
    <?= DetailView::widget([
        'model' => $model,
        'template' => function ($attribute, $index, $widget) {
            return "<tr><th style='max-width: 150px; word-wrap: break-word;'>{$attribute['label']}</th><td>{$attribute['value']}</td></tr>";
        },
        'attributes' => [
            [
                'label' => 'Наименование',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return DetailViewCopyHelper::render($model, 'name');
                }
            ],
            [
                'attribute' => '_pid',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return DetailViewCopyHelper::render($model, '_pid');
                }
            ],
            [
                'label' => 'P/E '
                    . Html::a('wiki', 'https://ru.wikipedia.org/wiki/%D0%A1%D0%BE%D0%BE%D1%82%D0%BD%D0%BE%D1%88%D0%B5%D0%BD%D0%B8%D0%B5_%C2%AB%D1%86%D0%B5%D0%BD%D0%B0_%E2%80%94_%D0%BF%D1%80%D0%B8%D0%B1%D1%8B%D0%BB%D1%8C%C2%BB')
                    . Html::tag('br')
                    . 'Показывает, сколько инвесторы готовы заплатить за единицу прибыли компании. <br> Проще говоря - за сколько лет окупятся ваши вложения в акции, если компания продолжит зарабатывать так же, как сейчас'
                ,
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\PEViewHelper::render($model);
                }
            ],
            [
                'label' => 'P/B',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\PBViewHelper::render($model);
                }
            ],
            [
                'label' => 'k1',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\K1ViewHelper::render($model);
                }
            ],
            [
                'label' => 'k2',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\K2ViewHelper::render($model);
                }
            ],
            [
                'label' => 'k3',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\K3ViewHelper::render($model);
                }
            ],
            [
                'label' => 'ROE',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\ROEViewHelper::render($model);
                }
            ],
            [
                'label' => 'D/E',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\DEViewHelper::render($model);
                }
            ],
            [
                'label' => 'P/CF',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\PCFViewHelper::render($model);
                }
            ],
            [
                'label' => 'P/S',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\PSViewHelper::render($model);
                }
            ],
        ],
    ]) ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
    });
');
