<?php

/** @var yii\web\View $this */
/** @var Issuer $model */
/** @var ActiveDataProvider $dataProvider */
/** @var ActiveDataProvider $availableFinancialReportsDataProvider */
/** @var AccountingBalanceCreateForm $createForm */
/** @var FinancialReportByApiCreateForm $apiCreateForm */

use lib\FrontendHelper\Icon;
use lib\FrontendHelper\LinkButtonPrinter;
use src\Action\Issuer\FinancialReport\AccountingBalance\AccountingBalanceCreateForm;
use src\Action\Issuer\FinancialReport\FinancialReportByApiCreateForm;
use src\Entity\Issuer\Issuer;
use src\Entity\User\UserRole;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumbs.homeLink'] = false;
$this->params['breadcrumbs'][] = ['label' => 'Эмитенты', 'url' => ['issuer/index']];
$this->params['breadcrumbs'][] = $model->name;
$this->title = 'Бухгалтерский баланс ' . $model->name;

?>

<?= $this->render('@views/_parts/issuer_tabs', [
    'model' => $model,
]); ?>
<?php if (Yii::$app->user->can(UserRole::admin->value)) : ?>
    <?= $this->render('@views/_parts/available-financial-reports', [
        'dataProvider' => $availableFinancialReportsDataProvider,
        'issuer' => $model,
    ]) ?>
    <?= $this->render('@views/_parts/create_by_api', [
        'createForm' => $apiCreateForm,
        'issuer' => $model,
        'url' => '/accounting-balance/fetch-external'
    ]) ?>
<?php endif; ?>

<div class="issuer-view">
    <?= LinkButtonPrinter::printExportCsv(Url::to(['/financial-report-export/accounting-balance', 'pid' => $model->_pid])) ?>
    <?= \app\widgets\ReversedFinancialReportTableWidget::widget([
        'models' => $dataProvider->getModels(),
        'modelClass' => \src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance::class,
        'saveAction' => Url::to(['/accounting-balance/create', 'issuerId' => $model->id]),
        'validateAction' => Url::to(['/accounting-balance/validate', 'issuerId' => $model->id]),
        'createForm' => $createForm,
    ]) ?>
</div>

