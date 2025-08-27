<?php

namespace app\controllers;

use lib\BaseController;
use src\CsvExporter\FinancialReportsCsvExporter;
use src\Entity\Issuer\FinanceReport\FinancialReportInterface;
use src\Entity\Issuer\Issuer;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class FinancialReportExportController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'accounting-balance',
                    'cash-flow',
                    'profit-loss',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'accounting-balance',
                            'cash-flow',
                            'profit-loss',
                        ],
                        'allow' => true,
                        'roles' => ['@', '?'],
                    ],
                ],
            ],
        ];
    }

    public function actionAccountingBalance(?string $pid): Response
    {
        $issuer = Issuer::getOneByCriteria(['_pid' => $pid]);
        $accountingBalanceReports = $issuer->accountBalanceReports;

        return $this->makeCsv("Бухгалтерский баланс {$issuer->name}", ...$accountingBalanceReports);
    }

    public function actionCashFlow(?string $pid): Response
    {
        $issuer = Issuer::getOneByCriteria(['_pid' => $pid]);
        $cashFlowReports = $issuer->cashFlowReports;

        if (empty($cashFlowReports)) {
            throw new NotFoundHttpException("Нет отчетностей у эмитента с УНП $pid");
        }

        return $this->makeCsv("О движении денежных средств {$issuer->name}", ...$cashFlowReports);
    }

    public function actionProfitLoss(?string $pid): Response
    {
        $issuer = Issuer::getOneByCriteria(['_pid' => $pid]);
        $profitLossReports = $issuer->profitLossReports;

        return $this->makeCsv("О прибылях и убытках {$issuer->name}", ...$profitLossReports);
    }

    private function makeCsv(string $fileName, FinancialReportInterface ...$reports): Response
    {
        if (empty($reports)) {
            throw new NotFoundHttpException("Нет отчетностей у эмитента");
        }

        $years = [];
        foreach ($reports as $report) {
            $years[] = $report->_year;
        }

        $fileName = str_replace('"', "", $fileName);

        return Yii::$app->response->sendContentAsFile(
            FinancialReportsCsvExporter::export(...$reports),
            $fileName . " за " . implode(', ', $years) . " год(а).csv",
            [
                'mimeType' => 'application/csv; charset=utf-8',
                'inline' => false,
            ]
        );
    }
}