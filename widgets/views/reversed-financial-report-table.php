<?php

use kartik\number\NumberControl;
use lib\FrontendHelper\DetailViewCopyHelper;
use lib\FrontendHelper\NullableValue;
use lib\FrontendHelper\SimpleNumberFormatter;
use src\Entity\Issuer\FinanceReport\FinancialReportInterface;
use src\Entity\User\UserRole;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var FinancialReportInterface[] $models */
/** @var string $saveAction */
/** @var string $validateAction */
/** @var \yii\base\Model $createForm */

$isAdmin = Yii::$app->user->can(UserRole::admin->value);
?>

<?php if (!empty($models)): ?>
<?php $form = ActiveForm::begin([
    'id' => 'finance-report-form',

    'action' => $saveAction,
    'validationUrl' => $validateAction,

    'enableAjaxValidation'      => true,
    'enableClientValidation'    => true,
    'validateOnChange'          => true,
    'validateOnSubmit'          => true,
    'validateOnBlur'            => true,
]); ?>
<table class="reversed-report-table table table-striped table-bordered">
    <thead>
        <tr>
            <th>Описание</th>
            <th>Код строки</th>
            <?php if ($isAdmin) : ?>
                <th>
                    <?= $form->field($createForm, 'year', [
                        'inputOptions' => [
                            'maxlength' => 4,
                        ],
                    ])->textInput(['class' => 'form-control'])->label(false) ?>
                </th>
            <?php endif; ?>
            <?php foreach ($models as $model) : ?>
                <?php $formattedYear = $model->getYear()->format('Y'); ?>
                <?php if ($isAdmin) : ?>
                    <th><?=
                        Html::a(
                            $formattedYear,
                            Yii::$app->request->get('year')
                                ? Url::current(['year' => null])
                                : Url::current(['year' => $formattedYear])
                        )
                    ?></th>
                <?php else: ?>
                    <th><?= $formattedYear ?></th>
                <?php endif; ?>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($model->getAttributesToShow() as $attributesGroupName => $attributes) : ?>
            <tr>
                <td><b><?= $attributesGroupName ?></b></td>
                <td></td>
                <?php if ($isAdmin) : ?>
                    <td></td>
                <?php endif; ?>
                <td colspan="<?= count($models) ?>"></td>
            </tr>
            <?php foreach ($attributes as $attribute => $attributeName) : ?>
            <tr>
                <td><?= $model->getAttributeLabel($attribute) ?></td>
                <td><?= str_replace('_', '', $attribute) ?></td>

                <?php if ($isAdmin) : ?>
                    <td class="create-data-row">
                        <?= $form->field($createForm, $attribute)->widget(
                            NumberControl::class,
                            Yii::$app->params['moneyWidgetOptions']
                        )->label(false); ?>
                    </td>
                <?php endif; ?>
                <?php foreach ($models as $model): ?>
                    <td>
                        <?=
                            $model->$attribute === null
                                ? NullableValue::printNull()
                                : DetailViewCopyHelper::renderValueColored(
                                        SimpleNumberFormatter::toViewWithSpaces($model->$attribute, 0)
                                )
                        ?>
                    </td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <?php if ($isAdmin) : ?>
            <tr>
                <td colspan="2"></td>
                <td><?= \yii\helpers\Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-block']) ?></td>
                <td colspan="<?= count($models) ?>"></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php ActiveForm::end() ?>
<?php endif; ?>