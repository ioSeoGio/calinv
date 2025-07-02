<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Issuer\Event\IssuerEventSearchForm;
use src\Action\Issuer\IssuerCreateForm;
use src\Action\Issuer\IssuerSearchForm;
use src\Action\Issuer\Rating\BusinessReputationInfoSearch;
use src\Action\Issuer\Rating\EsgRatingInfoSearch;
use src\Action\Issuer\UnreliableSupplier\UnreliableSupplierSearchForm;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\IssuerFactory;
use src\Integration\Bik\BusinessReputation\BusinessReputationRatingFetcher;
use src\Integration\Bik\EsgRating\EsgRatingFetcher;
use src\Integration\Egr\Event\EgrEventFetcher;
use src\Integration\Gias\UnreliableSupplier\GiasUnreliableSupplierFetcher;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

class IssuerController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        private IssuerFactory $factory,
        private BusinessReputationRatingFetcher $businessReputationRatingFetcher,
        private EsgRatingFetcher $esgRatingFetcher,
        private EgrEventFetcher $egrEventFetcher,
        private GiasUnreliableSupplierFetcher $giasUnreliableSupplierFetcher,

        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $searchForm = new IssuerSearchForm();
        $dataProvider = $searchForm->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchForm' => $searchForm,
            'dataProvider' => $dataProvider,

            'createForm' => new IssuerCreateForm(),
        ]);
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

        return $this->redirect(['/issuer/business-rating']);
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

        return $this->redirect(['/issuer/esg-rating']);
    }

    public function actionView($id): string
    {
        $issuer = Issuer::getOneById($id);

        $searchForm = new IssuerEventSearchForm();
        $eventDataProvider = $searchForm->search($issuer, Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $issuer,

            'searchForm' => $searchForm,
            'eventDataProvider' => $eventDataProvider,
        ]);
    }

    public function actionRenewIssuerEvents($id): Response
    {
        $issuer = Issuer::getOneById($id);
        $this->egrEventFetcher->update($issuer->pid);

        return $this->redirect(['issuer/view', 'id' => $issuer->id]);
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

        return $this->redirect(['issuer/unreliable-supplier']);
    }

    public function actionCreate(): Response
    {
        $form = new IssuerCreateForm();
        $post = Yii::$app->request->post();

        if ($form->load($post) && $form->validate()) {
            $this->factory->createOrUpdate($form);
        }

        return $this->redirect(['index']);
    }

    public function actionValidate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($post = Yii::$app->request->post()) {
            $simpleForm = new IssuerCreateForm();
            $simpleForm->load($post);

            return ActiveForm::validate($simpleForm);
        }
        return [];
    }
}