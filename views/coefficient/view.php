<?php

/** @var yii\web\View $this */
/** @var Issuer $model */
/** @var IssuerEventSearchForm $searchForm */
/** @var ActiveDataProvider $eventDataProvider */
/** @var ActiveDataProvider $importantEventDataProvider */

use lib\FrontendHelper\DetailViewCopyHelper;
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
            $description = $attribute['description'] ?? '';
            $link = $attribute['link'] ?? '';

            $content = Html::tag('th', $attribute['label'], ['style' => ['max-width' => '75px', 'word-wrap' => 'break-word']])
                . Html::tag('td', $attribute['value']);

            $thirdRowDescription = '';
            if ($link) {
                $thirdRowDescription .= Html::a('wiki', $link, ['target' => '_blank']) . Html::tag('br');
            }
            if ($description) {
                $thirdRowDescription .= $description;
            }

            $content .= Html::tag('td', $thirdRowDescription, ['style' => ['max-width' => '350px', 'word-wrap' => 'break-word']]);
            $result = Html::tag('tr', $content);
            return $result;
        },
        'attributes' => [
            [
                'label' => 'Наименование',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return DetailViewCopyHelper::render($model, 'name');
                },
            ],
            [
                'attribute' => '_pid',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return DetailViewCopyHelper::render($model, '_pid');
                },
            ],
            [
                'label' => 'P/E (Price/Earnings)',
                'link' => 'https://ru.wikipedia.org/wiki/%D0%A1%D0%BE%D0%BE%D1%82%D0%BD%D0%BE%D1%88%D0%B5%D0%BD%D0%B8%D0%B5_%C2%AB%D1%86%D0%B5%D0%BD%D0%B0_%E2%80%94_%D0%BF%D1%80%D0%B8%D0%B1%D1%8B%D0%BB%D1%8C%C2%BB',
                'description' => 'Показывает, сколько инвесторы готовы заплатить за единицу прибыли компании. 
                    <br> Проще говоря - за сколько лет окупятся ваши вложения в акции, если компания продолжит зарабатывать так же, как сейчас'
                ,
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\PEViewHelper::render($model);
                }
            ],
            [
                'label' => 'P/B (Price/Balance)',
                'link' => 'https://alfabank.ru/alfa-investor/t/chto-takoe-p-b/',
                'description' => 'Коэффициент P/B был введён для того, чтобы инвесторам было проще оценивать разницу между рыночной и бухгалтерской оценкой.',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\PBViewHelper::render($model);
                }
            ],
            [
                'label' => 'k1',
                'link' => 'https://ilex.by/raschet-koeffitsientov-platezhesposobnosti/',
                'description' => 'Коэффициент текущей ликвидности',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\K1ViewHelper::render($model);
                }
            ],
            [
                'label' => 'k2',
                'description' => 'Коэффициент обеспеченности собственными оборотными средствами',
                'link' => 'https://ilex.by/raschet-koeffitsientov-platezhesposobnosti/',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\K2ViewHelper::render($model);
                }
            ],
            [
                'label' => 'k3',
                'description' => 'Коэффициент обеспеченности обязательств активами',
                'link' => 'https://ilex.by/raschet-koeffitsientov-platezhesposobnosti/',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\K3ViewHelper::render($model);
                }
            ],
            [
                'label' => 'ROE (Return on Equity)',
                'link' => 'https://ru.wikipedia.org/wiki/%D0%A0%D0%B5%D0%BD%D1%82%D0%B0%D0%B1%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D1%8C_%D1%81%D0%BE%D0%B1%D1%81%D1%82%D0%B2%D0%B5%D0%BD%D0%BD%D0%BE%D0%B3%D0%BE_%D0%BA%D0%B0%D0%BF%D0%B8%D1%82%D0%B0%D0%BB%D0%B0',
                'description' => 'Финансовый коэффициент, отражающий эффективность управления компанией капиталом, вложенным акционерами.
                        <br>ROE дает оценку тому, сколько рублей прибыли генерируется на каждый рубль акционерного капитала.',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\ROEViewHelper::render($model);
                }
            ],
            [
                'label' => 'D/E (Debt/Equity)',
                'link' => 'https://ru.wikipedia.org/wiki/%D0%A0%D0%B5%D0%BD%D1%82%D0%B0%D0%B1%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D1%8C_%D1%81%D0%BE%D0%B1%D1%81%D1%82%D0%B2%D0%B5%D0%BD%D0%BD%D0%BE%D0%B3%D0%BE_%D0%BA%D0%B0%D0%BF%D0%B8%D1%82%D0%B0%D0%BB%D0%B0',
                'description' => 'Позволяет оценить, насколько компания зависит от заемных средств для финансирования своей деятельности по сравнению с собственным капиталом, который она вкладывает. 
                        <br>Другими словами, этот показатель указывает на то, сколько денег компания заимствует от кредиторов (через облигации, кредиты и т. д.) по отношению к средствам, вложенным ею самой (через акции и другие формы собственного капитала).',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\DEViewHelper::render($model);
                }
            ],
            [
                'label' => 'P/CF (Price/Free Cash)',
                'link' => 'https://bcs-express.ru/novosti-i-analitika/mul-tiplikator-p-cf-otsenka-po-denezhnym-potokam',
                'description' => 'Лучшая метрика для оценки способности компании выплачивать дивиденды и реализовывать программы buyback',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\PCFViewHelper::render($model);
                }
            ],
            [
                'label' => 'P/S (Price/Sales)',
                'link' => 'https://dzengi.com/ru/chto-takoe-koeffitsient-p-s',
                'description' => 'Этот показатель позволяет получить представление о том, сколько вы заплатите за один доллар выручки компании',
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
