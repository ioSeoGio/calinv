<?php

namespace app\controllers;

use lib\BaseController;
use src\CsvExporter\FinancialReportsCsvExporter;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Entity\Share\Deal\ShareDealRecord;
use src\Entity\Share\Share;
use src\Entity\Share\ShareRegisterNumber;
use src\Integration\Bcse\ShareInfo\BcseShareInfoFetcher;
use src\Integration\Legat\LegatAvailableFinancialReportsFetcherInterface;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class DevController extends BaseController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        private BcseShareInfoFetcher $bcseShareInfoFetcher,
        private LegatAvailableFinancialReportsFetcherInterface $fetcher,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
    }

    public function actionView(): string
    {
        $issuer = Issuer::getOneById(4);
        return $this->render('view', [
            'dataProvider' => new ActiveDataProvider(['query' => $issuer->getAccountBalanceReports(), 'pagination' => false]),
        ]);
    }

    public function actionCustomCsvExport()
    {
        $issuer = Issuer::getOneById(4);
        $models = $issuer->accountBalanceReports;

        $csvContent = FinancialReportsCsvExporter::export(...$models);

        return Yii::$app->response->sendContentAsFile(
            $csvContent,
            'exported_data.csv',
            [
                'mimeType' => 'application/csv; charset=utf-8',
                'inline' => false,
            ]
        );
    }

    public function actionTestData(int $shareId): string
    {
        $share = Share::getOneById($shareId);
        $data = ShareDealRecord::find()
            ->select(["timestamp", 'weightedAveragePrice'])
            ->andWhere(['share_id' => $shareId])
            ->addOrderBy(['timestamp' => SORT_ASC])
            ->asArray()->all();

        return json_encode([
            'shareName' => $share->getFormattedNameWithIssuer(),
            'values' => array_map(
                fn($item) => array_values($item),
                $data
            )
        ]);
    }

    public function actionIndex(): string
    {
        $r = $this->bcseShareInfoFetcher->get(new PayerIdentificationNumber('692041378'), new ShareRegisterNumber('6-404-01-15990'));
        dd($r);
    }
}