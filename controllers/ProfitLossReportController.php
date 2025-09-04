<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Issuer\FinancialReport\FinancialReportByApiCreateForm;
use src\Action\Issuer\FinancialReport\ProfitLossReport\ProfitLossReportCreateForm;
use src\Action\Issuer\FinancialReport\ProfitLossReport\ProfitLossReportSearchForm;
use src\Entity\Issuer\FinanceReport\AvailableFinancialReportData;
use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReportFactory;
use src\Entity\Issuer\Issuer;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\widgets\ActiveForm;

class ProfitLossReportController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        private ProfitLossReportFactory $factory,
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
        $form = new ProfitLossReportCreateForm($issuer, null);

        $post = Yii::$app->request->post();
        if ($form->load($post) && $form->validate()) {
            $this->factory->createOrUpdate($issuer, $form);
        }

        return $this->redirect(['index', 'issuerId' => $issuerId]);
    }

    public function actionIndex(?int $issuerId = null, ?string $unp = null, ?int $year = null): string
    {
        $issuer = $unp ? Issuer::getOneByPid($unp) : Issuer::getOneById($issuerId);

        $searchForm = new ProfitLossReportSearchForm();
        $dataProvider = $searchForm->search($issuer, Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $issuer,
            'dataProvider' => $dataProvider,
            'apiCreateForm' => new FinancialReportByApiCreateForm(),
            'availableFinancialReportsDataProvider' => new ActiveDataProvider([
                'query' => AvailableFinancialReportData::find()
                    ->andWhere(['issuerId' => $issuerId, 'hasProfitLossReport' => true])
                    ->addOrderBy(['_year' => SORT_DESC]),
                'pagination' => [
                    'pageSize' => 3
                ],
            ]),
            'createForm' => new ProfitLossReportCreateForm($issuer, $year),
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
            $simpleForm = new ProfitLossReportCreateForm($issuer, null);
            $simpleForm->load($post);

            $r = ActiveForm::validate($simpleForm);
            return $r;
        }
        return [];
    }
}