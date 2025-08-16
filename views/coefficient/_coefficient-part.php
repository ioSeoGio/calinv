<?php

use app\widgets\SimpleModal;
use lib\FrontendHelper\DetailViewCopyHelper;
use lib\FrontendHelper\Icon;
use src\Entity\Issuer\Issuer;
use src\ViewHelper\ComplexRating\ComplexRatingViewHelper;
use src\ViewHelper\ExpressRating\ExpressRatingViewHelper;
use src\ViewHelper\IssuerRating\IssuerBikRatingViewHelper;
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
            $description = $attribute['description'] ?? '';
            $link = $attribute['link'] ?? '';

            $content = Html::tag('th', $attribute['label'], ['style' => ['max-width' => '105px', 'word-wrap' => 'break-word']])
                . Html::tag('td', $attribute['value']);

            $thirdRowDescription = $link
                ? Html::a('wiki', $link, ['target' => '_blank']) . Html::tag('br')
                : '';
            $thirdRowDescription .= $description;

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
            $content .= Html::tag('td', $thirdRowDescription, ['style' => ['max-width' => '350px', 'word-wrap' => 'break-word']]);

            return Html::tag('tr', $content);
        },
        'attributes' => [
            [
                'label' => 'Наименование',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return DetailViewCopyHelper::renderValueColored($model->name);
                },
            ],
            [
                'attribute' => '_pid',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return DetailViewCopyHelper::renderValueColored($model->_pid);
                },
            ],
            [
                'label' => 'BIK',
                'format' => 'html',
                'value' => function (Issuer $model) {
                    return IssuerBikRatingViewHelper::render($model);
                },
                'visible' => !empty(IssuerBikRatingViewHelper::render($model)),
                'description' => Html::a('BIK', 'https://bikratings.by/', ['target' => '_blank']) . '<br>
                    Рейтинг деловой репутации, или esg рейтинг, или кредитный рейтинг.
                    <br>В таком порядке, если предыдущего рейтинга у эмитента нет
                ',
            ],
            [
                'label' => '<span title="Экспресс балл">Экспресс балл</span> '
                    . Html::a(Icon::printFaq(), Url::to(['/faq#express-rating'])),
                'headerOptions' => ['encode' => false],
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return ExpressRatingViewHelper::render($model, false);
                },
                'description' => 'Оценка по некоторым финансовым показателям<br>'
                    . Html::a('Подробнее', Url::to('/site/faq#express-rating'), ['target' => '_blank']),
            ],
            [
                'label' => '<span title="Комплексный балл">Комплексный балл</span> '
                    . Html::a(Icon::printFaq(), Url::to(['/faq#complex-rating'])),
                'headerOptions' => ['encode' => false],
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return ComplexRatingViewHelper::render($model);
                },
                'description' => 'Комплексная оценка по всем финансовым показателям, перечисленным ниже<br>'
                    . Html::a('Подробнее', Url::to('/site/faq#complex-rating'), ['target' => '_blank']),
            ],
            [
                'label' => 'P/E (Price/Earnings)',
                'formula' => \src\ViewHelper\IssuerCoefficient\PEViewHelper::getMathMLFormula(),
                'link' => 'https://ru.wikipedia.org/wiki/%D0%A1%D0%BE%D0%BE%D1%82%D0%BD%D0%BE%D1%88%D0%B5%D0%BD%D0%B8%D0%B5_%C2%AB%D1%86%D0%B5%D0%BD%D0%B0_%E2%80%94_%D0%BF%D1%80%D0%B8%D0%B1%D1%8B%D0%BB%D1%8C%C2%BB',
                'description' => '
                    Коэффициент, показывающий, сколько рублей инвестор готов заплатить за 1 рубль чистой прибыли компании в год. Простыми словами, он дает представление о том, за сколько лет могут окупиться ваши вложения при текущем уровне прибыли.
                    Важная оговорка!
                    Коэффициент P/E не учитывает множество факторов, таких как:<br>
                    <br>Рост или снижение прибыли компании в будущем.
                    <br>Дивидендные выплаты.
                    <br>Изменения в рыночных условиях и экономической среде.
                    <br>Другие финансовые показатели компании.
                    <br><br>
                    Таким образом, P/E — это скорее показатель текущей оценки компании инвесторами, а не точный индикатор срока окупаемости инвестиций, используется в сумме с другими мультипликаторами.'
                ,
                'format' => 'raw',
                'value' => function (Issuer $model) use ($capitalization) {
                    return \src\ViewHelper\IssuerCoefficient\PEViewHelper::render($model, $capitalization);
                }
            ],
            [
                'label' => 'P/B (Price/Balance)',
                'formula' => \src\ViewHelper\IssuerCoefficient\PBViewHelper::getMathMLFormula(),
                'link' => 'https://alfabank.ru/alfa-investor/t/chto-takoe-p-b/',
                'description' => '
                    Коэффициент, показывающий, во сколько раз больше/меньше инвестор заплатит за компанию по сравнению со стоимостью ее активов (здания, оборудование, финансовые активы и тд.).
                    <br>
                    <br>Если P/B = 1, это означает, что рыночная стоимость компании равна стоимости ее чистых активов.
                    <br>Если P/B > 1, это значит, что инвестор готов заплатить за компанию больше, чем стоимость ее чистых активов. Может указывать на переоцененность компании.
                    <br>Если P/B < 1, это значит, что инвестор заплатит за компанию меньше, чем стоимость ее чистых активов. Это может указывать на недооцененность компании.
                    <br>
                    <br>Важная оговорка!
                    <br>Коэффициент используется в сравнение с коэффициентами компаний-конкурентов из той же отрасли. Компания с наименьшим значением предпочтительней.
                ',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\IssuerCoefficient\PBViewHelper::render($model);
                }
            ],
            [
                'label' => 'k1',
                'formula' => \src\ViewHelper\IssuerCoefficient\K1ViewHelper::getMathMLFormula(),
                'link' => 'https://ilex.by/raschet-koeffitsientov-platezhesposobnosti/',
                'description' => 'Коэффициент текущей ликвидности',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\IssuerCoefficient\K1ViewHelper::render($model);
                }
            ],
            [
                'label' => 'k2',
                'formula' => \src\ViewHelper\IssuerCoefficient\K2ViewHelper::getMathMLFormula(),
                'description' => 'Коэффициент обеспеченности собственными оборотными средствами',
                'link' => 'https://ilex.by/raschet-koeffitsientov-platezhesposobnosti/',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\IssuerCoefficient\K2ViewHelper::render($model);
                }
            ],
            [
                'label' => 'k3',
                'formula' => \src\ViewHelper\IssuerCoefficient\K3ViewHelper::getMathMLFormula(),
                'description' => 'Коэффициент обеспеченности обязательств активами',
                'link' => 'https://ilex.by/raschet-koeffitsientov-platezhesposobnosti/',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\IssuerCoefficient\K3ViewHelper::render($model);
                }
            ],
            [
                'label' => 'ROE (Return on Equity)',
                'formula' => \src\ViewHelper\IssuerCoefficient\ROEViewHelper::getMathMLFormula(),
                'link' => 'https://ru.wikipedia.org/wiki/%D0%A0%D0%B5%D0%BD%D1%82%D0%B0%D0%B1%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D1%8C_%D1%81%D0%BE%D0%B1%D1%81%D1%82%D0%B2%D0%B5%D0%BD%D0%BD%D0%BE%D0%B3%D0%BE_%D0%BA%D0%B0%D0%BF%D0%B8%D1%82%D0%B0%D0%BB%D0%B0',
                'description' => '
                    Коэффициент, показывающий, насколько эффективно компания использует средства инвесторов, то есть сколько инвестор получает прибыли в год на каждый вложенный рубль. 
                    <br><br>Важная оговорка!
                    <br>Показатели ROE и ROA нужно анализировать в совокупности. Если долг компании увеличивается, ROE будет расти, а ROA падать.
                ',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\IssuerCoefficient\ROEViewHelper::render($model);
                }
            ],
            [
                'label' => 'ROA (Return on Asset)',
                'formula' => \src\ViewHelper\IssuerCoefficient\ROAViewHelper::getMathMLFormula(),
                'link' => 'https://ru.wikipedia.org/wiki/%D0%A0%D0%B5%D0%BD%D1%82%D0%B0%D0%B1%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D1%8C_%D0%B0%D0%BA%D1%82%D0%B8%D0%B2%D0%BE%D0%B2#:~:text=ROA%20%3D%20%D0%9E%D0%BF%D0%B5%D1%80%D0%B0%D1%86%D0%B8%D0%BE%D0%BD%D0%BD%D0%B0%D1%8F%20%D0%BF%D1%80%D0%B8%D0%B1%D1%8B%D0%BB%D1%8C%20%2F%D0%A1%D1%80%D0%B5%D0%B4%D0%BD%D1%8F%D1%8F%20%D1%81%D1%82%D0%BE%D0%B8%D0%BC%D0%BE%D1%81%D1%82%D1%8C,%D1%80%D1%83%D0%B1%D0%BB%D1%8F%2C%20%D0%BF%D0%BE%D1%82%D1%80%D0%B0%D1%87%D0%B5%D0%BD%D0%BD%D0%BE%D0%B3%D0%BE%20%D0%BD%D0%B0%20%D1%84%D0%BE%D1%80%D0%BC%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5%20%D0%B0%D0%BA%D1%82%D0%B8%D0%B2%D0%BE%D0%B2.',
                'description' => '
                    Коэффициент, показывающий, насколько эффективно компания использует свои собственные активы. Чем выше показатель, тем эффективнее работает бизнес и тем рентабельнее он использует свои активы.
                    <br><br>Важная оговорка!
                    <br>Показатели ROE и ROA нужно анализировать в совокупности. Если долг компании увеличивается, ROE будет расти, а ROA падать.
                ',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\IssuerCoefficient\ROAViewHelper::render($model);
                }
            ],
            [
                'label' => 'D/E (Debt/Equity)',
                'formula' => \src\ViewHelper\IssuerCoefficient\DEViewHelper::getMathMLFormula(),
                'link' => 'https://ru.wikipedia.org/wiki/%D0%A0%D0%B5%D0%BD%D1%82%D0%B0%D0%B1%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D1%8C_%D1%81%D0%BE%D0%B1%D1%81%D1%82%D0%B2%D0%B5%D0%BD%D0%BD%D0%BE%D0%B3%D0%BE_%D0%BA%D0%B0%D0%BF%D0%B8%D1%82%D0%B0%D0%BB%D0%B0',
                'description' => 'Позволяет оценить, насколько компания зависит от заемных средств для финансирования своей деятельности по сравнению с собственным капиталом, который она вкладывает. 
                        <br>Другими словами, этот показатель указывает на то, сколько денег компания заимствует от кредиторов (через облигации, кредиты и т. д.) по отношению к средствам, вложенным ею самой (через акции и другие формы собственного капитала).',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\IssuerCoefficient\DEViewHelper::render($model);
                }
            ],
            [
                'label' => 'P/OCF (Price/Operation Cash Flow)',
                'formula' => \src\ViewHelper\IssuerCoefficient\POCFViewHelper::getMathMLFormula(),
                'link' => 'https://bcs-express.ru/novosti-i-analitika/mul-tiplikator-p-cf-otsenka-po-denezhnym-potokam',
                'description' => '
                    Денежные потоки показывают, сколько на самом деле заработала компания вне зависимости 
                    от бухгалтерских манипуляций при расчете чистой прибыли
                    <br>Соотношение капитализации и операционного денежного потока (чистой прибыли по основной деятельности)
                ',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\IssuerCoefficient\POCFViewHelper::render($model);
                }
            ],
            [
                'label' => 'P/FCF (Price/Free Cash Flow)',
                'formula' => \src\ViewHelper\IssuerCoefficient\PFCFViewHelper::getMathMLFormula(),
                'link' => 'https://bcs-express.ru/novosti-i-analitika/mul-tiplikator-p-cf-otsenka-po-denezhnym-potokam',
                'description' => '
                    Денежные потоки показывают, сколько на самом деле заработала компания вне зависимости 
                    от бухгалтерских манипуляций при расчете чистой прибыли
                    <br>FCF — лучшая метрика для оценки способности компании выплачивать дивиденды и реализовывать программы buyback.
                    Соотношение капитализации и свободного денежного потока (чистой прибыли по всем видам деятельности)
                ',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\IssuerCoefficient\PFCFViewHelper::render($model);
                }
            ],
            [
                'label' => 'P/S (Price/Sales)',
                'formula' => \src\ViewHelper\IssuerCoefficient\PSViewHelper::getMathMLFormula(),
                'link' => 'https://dzengi.com/ru/chto-takoe-koeffitsient-p-s',
                'description' => '
                    Коэффициент позволяет получить представление о том, сколько инвесторы заплатят за один доллар выручки компании, или за сколько лет сумма выручки сравняется со стоимостью всей компании.
                    <br>Как и все мультипликаторы, коэффициент P/S наиболее значим при сравнении компаний в одном секторе экономики. Низкий коэффициент может указывать на недооцененность акций, в то время как высокий коэффициент говорит об их переоцененности.
                    <br><br>Важная оговорка!
                    <br>Коэффициент схож с P/E, однако обратите внимание, что учитывается выручка(доходность до вычета налогов), а не чистая прибыль.
                ',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return \src\ViewHelper\IssuerCoefficient\PSViewHelper::render($model);
                }
            ],
        ],
    ]) ?>
</div>