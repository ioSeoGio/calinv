<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Issuer\FinancialReport\AccountingBalance\AccountingBalanceCreateForm;
use src\Action\Issuer\FinancialReport\AccountingBalance\AccountingBalanceSearchForm;
use src\Action\Issuer\FinancialReport\FinancialReportByApiCreateForm;
use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;
use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalanceFactory;
use src\Entity\Issuer\Issuer;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Response;

class AccountingBalanceController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        private AccountingBalanceFactory $factory,
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
                ],
                'rules' => [
                    [
                        'actions' => [
                            'create',
                            'fetch-external',
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
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

    public function actionIndex(int $issuerId, ?int $year = null): string
    {
        $issuer = Issuer::getOneById($issuerId);

        $searchForm = new AccountingBalanceSearchForm();
        $dataProvider = $searchForm->search($issuer, Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $issuer,
            'dataProvider' => $dataProvider,
            'createForm' => new AccountingBalanceCreateForm($issuer, $year),
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