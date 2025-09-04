<?php

use lib\FrontendHelper\DetailViewCopyHelper;
use lib\FrontendHelper\GoodBadValueViewHelper;
use lib\FrontendHelper\NullableValue;
use lib\FrontendHelper\SimpleNumberFormatter;
use src\Action\Share\ShareCreateForm;
use src\Action\Share\ShareSearchForm;
use src\Entity\Share\Share;
use src\Entity\User\UserRole;
use src\Integration\Bcse\BcseUrlHelper;
use src\ViewHelper\Tools\Badge;
use yii\bootstrap5\Html;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Url;

/** @var ArrayDataProvider $shareDataProvider */
/** @var ShareSearchForm $shareSearchForm */
/** @var ShareCreateForm $shareCreateForm */
/** @var bool $showClosingDate */

$this->title = isset(Yii::$app->request->get('ShareSearchForm')['issuerId']) ? 'Акции эмитента' : 'Акции';
?>
<?= $this->render('../_parts/_tabs', []); ?>

<?php $columns = [
    [
        'label' => 'эмитент',
        'attribute' => 'issuer.name',
        'format' => 'raw',
        'value' => function (Share $model) {
            return Html::a($model->issuer->name, ['/issuer/view', 'unp' => $model->issuer->_pid]);
        },
        'filter' => Html::activeTextInput(
            $shareSearchForm,
            'issuerName',
            ['class' => 'form-control', 'placeholder' => 'Поиск по эмитенту...'],
        ),
    ],
    [
        'label' => 'Выпуск',
        'attribute' => 'formattedName',
        'format' => 'raw',
        'value' => function (Share $model) {
            return DetailViewCopyHelper::render($model, 'formattedName');
        },
    ],
    [
        'attribute' => 'registerNumber',
        'format' => 'raw',
        'value' => function (Share $model) {
            return DetailViewCopyHelper::render($model, 'registerNumber');
        },
    ],
    [
        'attribute' => 'lastDealDate',
        'format' => 'raw',
        'value' => function (Share $model) {
            return $model->lastDealDate
                ? Html::a(Yii::$app->formatter->asDate($model->lastDealDate), BcseUrlHelper::getShareUrl($model), ['target' => '_blank'])
                : null;
        }
    ],
    [
        'attribute' => 'lastDealChangePercent',
        'format' => 'raw',
        'value' => function (Share $model) {
            return $model->lastDealChangePercent !== null
                ? GoodBadValueViewHelper::asBadge($model->lastDealChangePercent, 0.00, true, '%')
                : NullableValue::printNull();
        }
    ],
    [
        'attribute' => 'currentPrice',
        'format' => 'html',
        'value' => function (Share $model) {
            $currentPrice = $model->currentPrice
                ? Badge::neutral($model->currentPrice . ' р.')
                : NullableValue::printNull();

            $minPrice = $model->minPrice
                ? Badge::neutral('мин ' . $model->minPrice . ' р.')
                : '';

            $maxPrice = $model->maxPrice
                ? Badge::neutral('макс ' . $model->maxPrice . ' р.')
                : '';

            if ($minPrice && $minPrice) {
                return $currentPrice . "<br>" . "$minPrice - $maxPrice";
            }

            return $currentPrice;
        },
        'contentOptions' => ['class' => 'text-left', 'style' => 'min-width: 250px'],
    ],
    [
        'attribute' => 'denomination',
        'format' => 'html',
        'value' => function (Share $model) {
            return $model->denomination
                ? Badge::neutral($model->denomination . ' р.')
                : NullableValue::printNull();
        }
    ],
    [
        'label' => 'Подробнее',
        'format' => 'raw',
        'value' => function (Share $model) {
            $shareDealsInfoExists = $model->getShareDeals()->count();

            $chart = $shareDealsInfoExists
                ? Html::a('График', Url::to(['/share/deal-info', 'id' => $model->id]), ['class' => 'btn btn-primary btn-sm border-bottom'])
                : '';

            $fairPrice = $shareDealsInfoExists && $model->issuer->getAccountBalanceReports()->count()
                ? '<br>' . Html::a('Расчет цены', Url::to(['/share/fair-price', 'id' => $model->id]), ['class' => 'btn btn-primary btn-sm border-bottom'])
                : '';

            $shareUrl = Yii::$app->user->can(UserRole::admin->value)
                ? '<br>' . Html::a(
                    'Биржа',
                    BcseUrlHelper::getShareUrl($model),
                    ['target' => '_blank']
                )
                : '';

            return $chart . $fairPrice . $shareUrl;
        },
        'options' => ['style' => 'min-width: 120px'],
    ],
    [
        'attribute' => 'totalIssuedAmount',
        'value' => function (Share $model) {
            return SimpleNumberFormatter::toView($model->totalIssuedAmount, 0) . ' шт.';
        }
    ],
    [
        'attribute' => 'issueDate',
        'format' => 'date',
    ],
]; ?>

<?php if ($showClosingDate): ?>
    <?php $columns = array_merge($columns, [
        [
            'attribute' => 'closingDate',
            'format' => 'date',
        ],
    ]); ?>
<?php endif ?>

<?= $sharesContent = GridView::widget([
    'dataProvider' => $shareDataProvider,
    'pager' => [
        'class' => \yii\bootstrap5\LinkPager::class,
    ],
    'filterModel' => $shareSearchForm,
    'columns' => $columns,
]) ?>