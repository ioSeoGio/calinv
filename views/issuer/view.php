<?php

/** @var yii\web\View $this */
/** @var Issuer $model */
/** @var IssuerEventSearchForm $searchForm */
/** @var ActiveDataProvider $eventDataProvider */
/** @var ActiveDataProvider $importantEventDataProvider */
/** @var ActiveDataProvider $employeeAmountDataProvider */
/** @var \yii\base\Model $descriptionEditForm */

use app\widgets\EditableHtmlAreaWidget;
use lib\FrontendHelper\DetailViewCopyHelper;
use lib\FrontendHelper\NullableValue;
use src\Action\Issuer\Event\IssuerEventSearchForm;
use src\Entity\Issuer\AdditionalInfo\IssuerLiquidationInfo;
use src\Entity\Issuer\EmployeeAmount\EmployeeAmountRecord;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\IssuerEvent\IssuerEvent;
use src\Entity\User\UserRole;
use src\ViewHelper\IssuerIcon\IssuerStateIconsPrinter;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
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
        'pager' => [
            'class' => \yii\bootstrap5\LinkPager::class,
        ],
        'columns' => [
            [
                'label' => 'Важные события',
                'attribute' => 'eventName',
            ],
            '_eventDate:date',
        ],
    ]) ?>

    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h2>Описание эмитента</h2>
            </div>
            <?php if (Yii::$app->user->can(UserRole::admin->value)): ?>
                <?php $form = ActiveForm::begin([
                    'action' => ['/issuer/edit-description', 'issuerId' => $model->id],
                ]) ?>

                <?= EditableHtmlAreaWidget::widget([
                    'form' => $form,
                    'model' => $descriptionEditForm,
                    'field' => 'description',
                ]) ?>

                <?= Html::submitButton('Сохранить описание', ['class' => 'btn btn-primary']) ?>

                <?php ActiveForm::end() ?>
            <?php else: ?>
                <?= $model->description ?>
            <?php endif; ?>
        </div>
    </div>
    <br>

    <?php if ($model->liquidationInfo !== null): ?>
        <?= DetailView::widget([
            'model' => $model->liquidationInfo,
            'attributes' => [
                [
                    'attribute' => 'liquidationDecisionNumber',
                    'format' => 'raw',
                    'value' => function (IssuerLiquidationInfo $model) {
                        return DetailViewCopyHelper::render($model, 'liquidationDecisionNumber');
                    }
                ],
                [
                    'attribute' => 'liquidatorName',
                    'format' => 'raw',
                    'value' => function (IssuerLiquidationInfo $model) {
                        return DetailViewCopyHelper::render($model, 'liquidatorName');
                    }
                ],
                [
                    'attribute' => 'liquidatorAddress',
                    'format' => 'raw',
                    'value' => function (IssuerLiquidationInfo $model) {
                        return DetailViewCopyHelper::render($model, 'liquidatorAddress');
                    }
                ],
                [
                    'attribute' => 'liquidatorPhone',
                    'format' => 'raw',
                    'value' => function (IssuerLiquidationInfo $model) {
                        return \src\ViewHelper\IssuerPhonesViewHelper::render($model->liquidatorPhone);
                    }
                ],
                '_beginDate:date',
                '_publicationDate:date',
                [
                    'attribute' => 'periodForAcceptingClaimsInMonths',
                    'format' => 'raw',
                    'value' => function (IssuerLiquidationInfo $model) {
                        $interval = DateInterval::createFromDateString($model->periodForAcceptingClaimsInMonths . 'months');
                        $lastDate = $model->publicationDate->add($interval);

                        return $model->periodForAcceptingClaimsInMonths . ' месяца'
                            . "<br>"
                            . "До: " . Yii::$app->formatter->asDate($lastDate, 'long');
                    },
                ],
                'status',
            ],
        ]) ?>
    <?php endif; ?>

    <?php
        $attributes = [
            [
                'label' => '',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    if (Yii::$app->user->can(\src\Entity\User\UserRole::admin->value)) {
                        return Html::a('Обновить общую информацию', ['update-issuer-info', 'id' => $model->id], ['class' => 'btn btn-success']);
                    }

                    return '';
                }
            ],
            [
                'label' => 'Наименование',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    return
                        IssuerStateIconsPrinter::printMany($model)
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
                        $result = Html::tag(
                            'span',
                            Yii::$app->formatter->asBoolean((bool) $model->dateShareInfoModerated)
                                . ', '
                                . Yii::$app->formatter->asRelativeTime($model->dateShareInfoModerated),
                            ['class' => 'btn btn-success']
                        );

                        if (Yii::$app->user->can(UserRole::admin->value)) {
                            $result .= '<br>' . Html::a("Пометить ненадежными", ['share/toggle-moderation', 'issuerId' => $model->id], ['class' => 'btn btn-danger']);
                        }

                        return $result;
                    }

                    $result = Html::tag(
                        'span',
                        Yii::$app->formatter->asBoolean((bool) $model->dateShareInfoModerated),
                        ['class' => 'btn btn-danger']
                    );

                    if (Yii::$app->user->can(UserRole::admin->value)) {
                        $result .= '<br>' . Html::a("Пометить надежными", ['share/toggle-moderation', 'issuerId' => $model->id], ['class' => 'btn btn-success']);
                    }

                    return $result;
                },
            ],
            [
                'label' => '',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    $result = '';

                    if (Yii::$app->user->can(UserRole::admin->value)) {
                        $result .= Html::a(
                                "Обновить акции",
                                ['update-issuer-info', 'id' => $model->id],
                                ['class' => 'btn btn-success']
                            ) . '<br>';
                    }

                    return $result . Html::a("Активные акции ({$model->getActiveShares()->count()})", ['/share', 'ShareSearchForm' => [
                            'issuerId' =>  $model->id,
                        ]], ['target' => '_blank', 'class' => 'btn btn-primary'])
                        . '<hr>'
                        . Html::a("Все акции ({$model->getShares()->count()})", ['/share/all-shares', 'ShareSearchForm' => [
                            'issuerId' =>  $model->id,
                        ]], ['target' => '_blank', 'class' => 'btn btn-secondary']);
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

//            [
//                'label' => '',
//                'format' => 'raw',
//                'value' => function (Issuer $model) {
//                    if (Yii::$app->user->can(UserRole::admin->value)) {
//                        return Html::a('Обновить вид деятельности', ['renew-type-of-activity', 'id' => $model->id], ['class' => 'btn btn-success']);
//                    }
//
//                    return '';
//                }
//            ],
//            'typeOfActivity.name',
//            'typeOfActivity.code',

            [
                'label' => '',
                'format' => 'raw',
                'value' => function (Issuer $model) {
                    if (Yii::$app->user->can(UserRole::admin->value)) {
                        return Html::a('Обновить через Legat (платно)', ['renew-legat-issuer-info', 'id' => $model->id], ['class' => 'btn btn-success']);
                    }

                    return '';
                }
            ],
        ];

        if ($model->addressInfo !== null) {
            $attributes = array_merge($attributes, [
                'addressInfo.fullAddress',
                [
                    'attribute' => 'addressInfo.phones',
                    'format' => 'raw',
                    'value' => function (Issuer $model) {
                        return \src\ViewHelper\IssuerPhonesViewHelper::render($model->addressInfo->phones);
                    }
                ],
                'addressInfo.email',
                [
                    'attribute' => 'addressInfo.site',
                    'format' => 'raw',
                    'value' =>  function (Issuer $model) {
                        if ($model->addressInfo?->site === null) {
                            return null;
                        }

                        $siteUrl = explode(',', $model->addressInfo?->site);

                        $result = '';
                        if (is_array($siteUrl)) {
                            foreach ($siteUrl as $site) {
                                $result .= Html::a($site, \lib\UrlGenerator::addProtocolIfNeeded($site), ['target' => '_blank'])
                                    . Html::tag('br');
                            }
                        } else {
                            $result .= Html::a($siteUrl, \lib\UrlGenerator::addProtocolIfNeeded($siteUrl), ['target' => '_blank']);
                        }

                        return $result;
                    }
                ],
            ]);
        }

        if ($model->additionalInfo !== null) {
            $attributes = array_merge($attributes, [
                'additionalInfo.directorName',
                'additionalInfo.isBankrupting:boolean',
                [
                    'label' => 'Кол-во судов приказного делопроизводства',
                    'format' => 'raw',
                    'value' => function (Issuer $model) {
                        return "Как взыскатель: " . NullableValue::print($model->additionalInfo?->orderlyCourtAmountAsClaimant)
                            . "<br>"
                            . "Как должник: " . NullableValue::print($model->additionalInfo?->orderlyCourtAmountAsDebtor);
                    }
                ],
                [
                    'label' => 'Кол-во судов искового делопроизводства',
                    'format' => 'raw',
                    'value' => function (Issuer $model) {
                        return "Как истец: " . NullableValue::print($model->additionalInfo?->courtAmountAsPlaintiff)
                            . "<br>"
                            . "Как ответчик: " . NullableValue::print($model->additionalInfo?->courtAmountAsDefendant)
                            . "<br>"
                            . "Другое: " . NullableValue::print($model->additionalInfo?->courtAmountOther);
                    }
                ],
                [
                    'label' => 'Архив закупок',
                    'format' => 'raw',
                    'value' => function (Issuer $model) {
                        return "Как поставщик: " . NullableValue::print($model->additionalInfo?->purchaseArchiveAsSupplierAmount)
                            . "<br>"
                            . "Как заказчик: " . NullableValue::print($model->additionalInfo?->purchaseArchiveAsCustomerAmount)
                            . "<br>"
                            . "Как участник (не выбран исполнителем): " . NullableValue::print($model->additionalInfo?->purchaseArchiveAsMemberAmount);
                    }
                ],
                [
                    'label' => 'Реестр договоров (данные icetrade.by)',
                    'format' => 'raw',
                    'value' => function (Issuer $model) {
                        return "Как поставщик: " . NullableValue::print($model->additionalInfo?->registerOfContractsAsSupplierAmount)
                            . "<br>"
                            . "Как заказчик: " . NullableValue::print($model->additionalInfo?->registerOfContractsAsCustomerAmount);
                    }
                ],
                [
                    'attribute' => 'additionalInfo.retailFacilities',
                    'format' => 'raw',
                    'value' => function (Issuer $model) {
                        if (empty($model->additionalInfo->retailFacilities)) {
                            return null;
                        }

                        return implode('<br>', $model->additionalInfo->retailFacilities);
                    }
                ],
                [
                    'label' => 'Проверки КГК',
                    'format' => 'raw',
                    'value' => function (Issuer $model) {
                        return "Запланированные: " . NullableValue::print($model->additionalInfo?->kgkPlannedInspectionAmount)
                            . "<br>"
                            . "Завершенные: " . NullableValue::print($model->additionalInfo?->kgkEndedInspectionAmount);
                    }
                ],
                [
                    'attribute' => 'additionalInfo.debtTaxes',
                    'format' => 'raw',
                    'value' => function (Issuer $model) {
                        return $model->additionalInfo?->debtTaxes
                            ? implode('<br>', $model->additionalInfo?->debtTaxes)
                            : null;
                    }
                ],
                [
                    'attribute' => 'additionalInfo.debtFszn',
                    'format' => 'raw',
                    'value' => function (Issuer $model) {
                        if (empty($model->additionalInfo?->debtFszn)) {
                            return null;
                        }

                        $result = '';
                        foreach ($model->additionalInfo?->debtFszn as $fszn) {
                            $result .= "Долг: {$fszn['amount']} на дату {$fszn['date']}<br>";
                        }

                        return $result;
                    }
                ],
                [
                    'attribute' => 'additionalInfo.increasedEconomicOffense',
                    'format' => 'raw',
                    'value' => function (Issuer $model) {
                        if (empty($model->additionalInfo?->increasedEconomicOffense)) {
                            return null;
                        }

                        $result = '';
                        foreach ($model->additionalInfo?->increasedEconomicOffense as $increasedEconomicOffense) {
                            $result .= "Причина: {$increasedEconomicOffense['reason']} на дату {$increasedEconomicOffense['date']}";

                            if ($increasedEconomicOffense['isExcludedFromRegister']) {
                                $result .= ", на данный момент исключен из реестра";
                            }
                            $result .= "<br>";
                        }

                        return $result;
                    },
                ],
                'additionalInfo.trademarksRegisteredAmount',
                'additionalInfo.industrialProductsAmount',
                'additionalInfo.softRegisteredAmount',
                'additionalInfo.foreignBranchesRfAmount',
            ]);
        }
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes
    ]) ?>
    <hr>

    <div>
        Время последнего обновления: <?= Yii::$app->formatter->asDatetime(EmployeeAmountRecord::getLastUpdateSessionDate(['issuerId' => $model->id])) ?>
        <?php if (Yii::$app->user->can(UserRole::admin->value)): ?>
            <div>
                <?= Html::a('Обновить через Legat (платно)', ['renew-employee-amount', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            </div>
        <?php endif; ?>
    </div>
    <?= GridView::widget([
        'dataProvider' => $employeeAmountDataProvider,
        'pager' => [
            'class' => \yii\bootstrap5\LinkPager::class,
        ],
        'columns' => [
            'amount',
            '_date:date',
        ],
    ]) ?>

    <hr>
    <div>
        Время последнего обновления: <?= Yii::$app->formatter->asDatetime(IssuerEvent::getLastUpdateSessionDate(['_pid' => $model->_pid])) ?>
        <?php if (Yii::$app->user->can(UserRole::admin->value)): ?>
            <div>
                <?= Html::a('Обновить через Legat (платно)', ['renew-issuer-events', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            </div>
        <?php endif; ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $eventDataProvider,
        'pager' => [
            'class' => \yii\bootstrap5\LinkPager::class,
        ],
        'filterModel' => $searchForm,
        'columns' => [
            'eventName',
            '_eventDate:date',
        ],
    ]) ?>
</div>

<?php $this->registerJs('
    $(document).ready(function() {
    });
');
