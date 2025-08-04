<?php

use lib\FrontendHelper\DetailViewCopyHelper;
use lib\FrontendHelper\SimpleNumberFormatter;
use src\Entity\Issuer\FinanceReport\FinancialReportInterface;

/** @var FinancialReportInterface[] $models */
?>

<style>
.reversed-report-table th:first-child,
.reversed-report-table td:first-child {
    width: 250px; /* Укажите желаемую ширину */
}
.reversed-report-table th:nth-child(2),
.reversed-report-table td:nth-child(2) {
    width: 70px; /* Укажите желаемую ширину */
    text-align: center;
}
.reversed-report-table td:not(:first-child),
.reversed-report-table th:not(:first-child) {
    text-align: center;
}
</style>

<?php if (!empty($models)): ?>
<table class="reversed-report-table table table-striped table-bordered">
    <thead>
        <tr>
            <th>Описание</th>
            <th>Код строки</th>
            <?php foreach ($models as $model) : ?>
                <th><?= $model->getYear()->format('Y') ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($model->getAttributesToShow() as $key => $attribute) : ?>
            <tr data-key="<?= $key ?>">
                <td><?= $model->getAttributeLabel($attribute) ?></td>
                <td><?= str_replace('_', '', $attribute) ?></td>

                <?php foreach ($models as $model) : ?>
                    <td>
                        <?= DetailViewCopyHelper::renderValue(
                            SimpleNumberFormatter::toViewWithSpaces($model->$attribute, 0)
                        ) ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>