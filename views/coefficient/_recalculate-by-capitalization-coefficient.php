<?php

use app\widgets\SimpleModal;
use lib\FrontendHelper\DetailViewCopyHelper;
use lib\FrontendHelper\Icon;
use src\Entity\Issuer\Issuer;
use src\ViewHelper\ComplexRating\ComplexRatingViewHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var \src\Entity\Issuer\Issuer $model */

$capitalization = $capitalization ?? null;
?>
<div id="coefficients-container">
    <?= DetailView::widget([
        'model' => $model,
        'template' => function ($attribute, $index, $widget) {
            $content = Html::tag('th', $attribute['label'], ['style' => ['max-width' => '105px', 'word-wrap' => 'break-word']])
                . Html::tag('td', $attribute['value']);

            if ($attribute['formula'] ?? '') {
                $formulaContent = SimpleModal::widget([
                    'id' => $index,
                    'title' => 'Формула расчета',
                    'body' => $attribute['formula'] ?? '',
                ]);
                $formulaContent .= SimpleModal::renderButton('Формула', $index);
            } else {
                $formulaContent = '';
            }
            $content .= Html::tag('td', $formulaContent, ['style' => ['max-width' => '150px', 'word-wrap' => 'break-word']]);

            return Html::tag('tr', $content);
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
                'label' => '<span title="Комплексный балл">Комплексный балл</span> '
                    . Html::a(Icon::printFaq(), Url::to(['/faq#complex-rating'])),
                'headerOptions' => ['encode' => false],
                'format' => 'raw',
                'value' => function (Issuer $model) use ($capitalization) {
                    return ComplexRatingViewHelper::render($model, $capitalization);
                },
            ],
            [
                'label' => 'P/E (Price/Earnings)',
                'formula' => \src\ViewHelper\IssuerCoefficient\PEViewHelper::getMathMLFormula(),
                'format' => 'raw',
                'value' => function (Issuer $model) use ($capitalization) {
                    return \src\ViewHelper\IssuerCoefficient\PEViewHelper::render($model, $capitalization);
                }
            ],
            [
                'label' => 'P/B (Price/Balance)',
                'formula' => \src\ViewHelper\IssuerCoefficient\PBViewHelper::getMathMLFormula(),
                'format' => 'raw',
                'value' => function (Issuer $model) use ($capitalization){
                    return \src\ViewHelper\IssuerCoefficient\PBViewHelper::render($model, $capitalization);
                }
            ],
            [
                'label' => 'P/OCF (Price/Operation Cash Flow)',
                'formula' => \src\ViewHelper\IssuerCoefficient\POCFViewHelper::getMathMLFormula(),
                'format' => 'raw',
                'value' => function (Issuer $model) use ($capitalization) {
                    return \src\ViewHelper\IssuerCoefficient\POCFViewHelper::render($model, $capitalization);
                }
            ],
            [
                'label' => 'P/FCF (Price/Free Cash Flow)',
                'formula' => \src\ViewHelper\IssuerCoefficient\PFCFViewHelper::getMathMLFormula(),
                'format' => 'raw',
                'value' => function (Issuer $model) use ($capitalization) {
                    return \src\ViewHelper\IssuerCoefficient\PFCFViewHelper::render($model, $capitalization);
                }
            ],
            [
                'label' => 'P/S (Price/Sales)',
                'formula' => \src\ViewHelper\IssuerCoefficient\PSViewHelper::getMathMLFormula(),
                'format' => 'raw',
                'value' => function (Issuer $model) use ($capitalization) {
                    return \src\ViewHelper\IssuerCoefficient\PSViewHelper::render($model, $capitalization);
                }
            ],
        ],
    ]) ?>
</div>