<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Issuer\Rating\BusinessReputationInfoSearch;
use src\Action\Issuer\Rating\EsgRatingInfoSearch;
use src\Action\Issuer\UnreliableSupplier\UnreliableSupplierSearchForm;
use src\Integration\Bik\BusinessReputation\BusinessReputationRatingFetcher;
use src\Integration\Bik\EsgRating\EsgRatingFetcher;
use src\Integration\Gias\UnreliableSupplier\GiasUnreliableSupplierFetcher;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

class IssuerRatingController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        private BusinessReputationRatingFetcher $businessReputationRatingFetcher,
        private EsgRatingFetcher $esgRatingFetcher,
        private GiasUnreliableSupplierFetcher $giasUnreliableSupplierFetcher,

        $config = []
    )
    {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'renew-business-rating',
                    'renew-esg-rating',
                    'renew-unreliable-supplier',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'renew-business-rating',
                            'renew-esg-rating',
                            'renew-unreliable-supplier',
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionBusinessRating(): string
    {
        $searchForm = new BusinessReputationInfoSearch();
        $dataProvider = $searchForm->search(Yii::$app->request->queryParams);

        return $this->render('issuer_business_rating', [
            'searchForm' => $searchForm,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRenewBusinessRating(): Response
    {
        $this->businessReputationRatingFetcher->updateRatings();

        return $this->redirect(['/issuer-rating/business-rating']);
    }

    public function actionEsgRating(): string
    {
        $searchForm = new EsgRatingInfoSearch();
        $dataProvider = $searchForm->search(Yii::$app->request->queryParams);

        return $this->render('issuer_esg_rating', [
            'searchForm' => $searchForm,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRenewEsgRating(): Response
    {
        $this->esgRatingFetcher->updateRatings();

        return $this->redirect(['/issuer-rating/esg-rating']);
    }

    public function actionUnreliableSupplier(): string
    {
        $searchForm = new UnreliableSupplierSearchForm();
        $dataProvider = $searchForm->search(Yii::$app->request->queryParams);

        return $this->render('issuer_unreliable_supplier', [
            'searchForm' => $searchForm,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRenewUnreliableSupplier(): Response
    {
        $this->giasUnreliableSupplierFetcher->update();

        return $this->redirect(['issuer-rating/unreliable-supplier']);
    }
}