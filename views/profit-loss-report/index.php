<?php

/** @var yii\web\View $this */
/** @var Issuer $model */
/** @var ActiveDataProvider $dataProvider */
/** @var ActiveDataProvider $availableFinancialReportsDataProvider */
/** @var FinancialReportByApiCreateForm $apiCreateForm */
/** @var ProfitLossReportCreateForm $createForm */

use lib\FrontendHelper\LinkButtonPrinter;
use lib\MetaTag\MetaTagManager;
use src\Action\Issuer\FinancialReport\FinancialReportByApiCreateForm;
use src\Action\Issuer\FinancialReport\ProfitLossReport\ProfitLossReportCreateForm;
use src\Entity\Issuer\Issuer;
use src\Entity\User\UserRole;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

MetaTagManager::registerIssuerTags($this, $model);

$this->params['breadcrumbs.homeLink'] = false;
$this->params['breadcrumbs'][] = ['label' => 'Эмитенты', 'url' => ['issuer/index']];
$this->params['breadcrumbs'][] = $model->name;
$this->title = 'Отчет о прибылях и убытках ' . $model->name . " УНП $model->_pid";
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
        'url' => '/profit-loss-report/fetch-external'
    ]) ?>
<?php endif; ?>

<div class="issuer-view">
    <?= LinkButtonPrinter::printExportCsv(Url::to(['/financial-report-export/profit-loss', 'pid' => $model->_pid])) ?>
    <?= \app\widgets\ReversedFinancialReportTableWidget::widget([
        'models' => $dataProvider->getModels(),
        'modelClass' => \src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReport::class,
        'saveAction' => Url::to(['/profit-loss-report/create', 'issuerId' => $model->id]),
        'validateAction' => Url::to(['/profit-loss-report/validate', 'issuerId' => $model->id]),
        'createForm' => $createForm,
    ]) ?>
</div>

