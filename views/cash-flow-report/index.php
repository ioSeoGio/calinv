<?php

/** @var yii\web\View $this */
/** @var Issuer $model */
/** @var ActiveDataProvider $dataProvider */
/** @var ActiveDataProvider $availableFinancialReportsDataProvider */
/** @var FinancialReportByApiCreateForm $apiCreateForm */
/** @var \src\Action\Issuer\FinancialReport\CashFlowReport\CashFlowReportCreateForm $createForm */

use lib\FrontendHelper\LinkButtonPrinter;
use lib\MetaTag\MetaTagManager;
use src\Action\Issuer\FinancialReport\FinancialReportByApiCreateForm;
use src\Entity\Issuer\Issuer;
use src\Entity\User\UserRole;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

MetaTagManager::registerIssuerTags($this, $model);

$this->params['breadcrumbs.homeLink'] = false;
$this->params['breadcrumbs'][] = ['label' => 'Эмитенты', 'url' => ['issuer/index']];
$this->params['breadcrumbs'][] = $model->name;
$this->title = 'Отчет о движении денежных средств (ДДС) ' . " УНП $model->_pid";
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
        'url' => '/cash-flow-report/fetch-external'
    ]) ?>
<?php endif; ?>

<div class="issuer-view">
    <?= LinkButtonPrinter::printExportCsv(Url::to(['/financial-report-export/cash-flow', 'pid' => $model->_pid])) ?>
    <?= \app\widgets\ReversedFinancialReportTableWidget::widget([
        'models' => $dataProvider->getModels(),
        'modelClass' => \src\Entity\Issuer\FinanceReport\CashFlowReport\CashFlowReport::class,
        'saveAction' => Url::to(['/cash-flow-report/create', 'issuerId' => $model->id]),
        'validateAction' => Url::to(['/cash-flow-report/validate', 'issuerId' => $model->id]),
        'createForm' => $createForm,
    ]) ?>
</div>
