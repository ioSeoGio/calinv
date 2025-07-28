<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Issuer\FinancialReport\CashFlowReport\CashFlowReportSearchForm;
use src\Action\Issuer\FinancialReport\FinancialReportByApiCreateForm;
use src\Action\Issuer\FinancialReport\ProfitLossReport\ProfitLossReportSearchForm;
use src\Entity\Issuer\FinanceReport\CashFlowReport\CashFlowReportFactory;
use src\Entity\Issuer\Issuer;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

class CashFlowReportController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        private CashFlowReportFactory $factory,
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
                    'fetch-external',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'fetch-external',
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($issuerId): string
    {
        $issuer = Issuer::getOneById($issuerId);

        $searchForm = new CashFlowReportSearchForm();
        $dataProvider = $searchForm->search($issuer, Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $issuer,
            'dataProvider' => $dataProvider,
            'apiCreateForm' => new FinancialReportByApiCreateForm(),
        ]);
    }

    public function actionFetchExternal(int $issuerId): Response
    {
        $issuer = Issuer::getOneById($issuerId);
        $createForm = new FinancialReportByApiCreateForm();

        if ($createForm->load(Yii::$app->request->post()) && $createForm->validate()) {
            $this->factory->createOrUpdateByExternalApi($issuer, \DateTimeImmutable::createFromFormat('Y', $createForm->year));
        }

        return $this->redirect(['index', 'issuerId' => $issuerId]);
    }
}