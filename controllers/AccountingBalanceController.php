<?php

namespace app\controllers;

use lib\BaseController;
use lib\FlashType;
use src\Action\Issuer\FinancialReport\AccountingBalance\AccountingBalanceCreateForm;
use src\Action\Issuer\FinancialReport\AccountingBalance\AccountingBalanceSearchForm;
use src\Action\Issuer\FinancialReport\FinancialReportByApiCreateForm;
use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalanceFactory;
use src\Entity\Issuer\FinanceReport\AvailableFinancialReportData;
use src\Entity\Issuer\FinanceReport\AvailableFinancialReportFactory;
use src\Entity\Issuer\Issuer;
use src\Integration\Legat\Api\LegatAvailableFinancialReportsFetcher;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Response;

class AccountingBalanceController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        private AccountingBalanceFactory $factory,
        private LegatAvailableFinancialReportsFetcher $legatAvailableFinancialReportsFetcher,
        private AvailableFinancialReportFactory $availableFinancialReportFactory,
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
                    'create',
                    'fetch-external',
                    'renew-available-financial-reports',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'create',
                            'fetch-external',
                            'renew-available-financial-reports',
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionRenewAvailableFinancialReports($issuerId): Response
    {
        $issuer = Issuer::getOneById($issuerId);
        $allDtos = $this->legatAvailableFinancialReportsFetcher->getAvailableReports($issuer->pid);

        if (empty($allDtos->records)) {
            Yii::$app->session->addFlash(FlashType::info->value, 'Для эмитента нет доступных фин. отчетов');
        }

        $this->availableFinancialReportFactory->createOrUpdateBulk($issuer, $allDtos);

        return $this->redirect(['index', 'issuerId' => $issuerId]);
    }

    public function actionCreate($issuerId): Response
    {
        $issuer = Issuer::getOneById($issuerId);
        $form = new AccountingBalanceCreateForm($issuer, null);

        $post = Yii::$app->request->post();
        if ($form->load($post) && $form->validate()) {
            $this->factory->createOrUpdate($issuer, $form);
        }

        return $this->redirect(['index', 'issuerId' => $issuerId]);
    }

    public function actionIndex(?int $issuerId = null, ?string $unp = null, ?int $year = null): string
    {
        $issuer = $unp ? Issuer::getOneByPid($unp) : Issuer::getOneById($issuerId);

        $searchForm = new AccountingBalanceSearchForm();
        $dataProvider = $searchForm->search($issuer, Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $issuer,
            'dataProvider' => $dataProvider,
            'createForm' => new AccountingBalanceCreateForm($issuer, $year),
            'availableFinancialReportsDataProvider' => new ActiveDataProvider([
                'query' => AvailableFinancialReportData::find()
                    ->andWhere(['issuerId' => $issuerId, 'hasAccountingBalance' => true])
                    ->addOrderBy(['_year' => SORT_DESC]),
                'pagination' => [
                    'pageSize' => 3
                ],
            ]),
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

    public function actionValidate(int $issuerId): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $issuer = Issuer::getOneById($issuerId);

        if ($post = Yii::$app->request->post()) {
            $simpleForm = new AccountingBalanceCreateForm($issuer, null);
            $simpleForm->load($post);

            $r = ActiveForm::validate($simpleForm);
            return $r;
        }
        return [];
    }
}