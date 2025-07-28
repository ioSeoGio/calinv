<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Issuer\Event\IssuerEventSearchForm;
use src\Action\Issuer\IssuerCreateForm;
use src\Action\Issuer\IssuerDescriptionEditForm;
use src\Action\Issuer\IssuerSearchForm;
use src\Action\Issuer\Rating\BusinessReputationInfoSearch;
use src\Action\Issuer\Rating\EsgRatingInfoSearch;
use src\Action\Issuer\UnreliableSupplier\UnreliableSupplierSearchForm;
use src\Entity\Issuer\AddressInfo\ApiAddressInfoFactory;
use src\Entity\Issuer\ApiIssuerInfoAndSharesFactory;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\IssuerFactory;
use src\Entity\Issuer\TypeOfActivity\ApiTypeOfActivityFactory;
use src\Integration\Bik\BusinessReputation\BusinessReputationRatingFetcher;
use src\Integration\Bik\EsgRating\EsgRatingFetcher;
use src\Integration\Egr\Event\EgrEventFetcher;
use src\Integration\Gias\UnreliableSupplier\GiasUnreliableSupplierFetcher;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
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
        private ApiAddressInfoFactory $apiAddressInfoFactory,
        private ApiTypeOfActivityFactory $apiTypeOfActivityFactory,
        private ApiIssuerInfoAndSharesFactory $apiIssuerInfoAndSharesFactory,

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
                    'renew-business-rating',
                    'renew-esg-rating',
                    'renew-issuer-events',
                    'renew-unreliable-supplier',
                    'renew-address',
                    'renew-type-of-activity',
                    'edit-description',
                    'update-issuer-info',
                    'create',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'renew-business-rating',
                            'renew-esg-rating',
                            'renew-issuer-events',
                            'renew-unreliable-supplier',
                            'renew-address',
                            'renew-type-of-activity',
                            'edit-description',
                            'update-issuer-info',
                            'create',
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
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
        $importantEventDataProvider = $searchForm->searchImportant($issuer);

        return $this->render('view', [
            'model' => $issuer,

            'searchForm' => $searchForm,
            'descriptionEditForm' => new IssuerDescriptionEditForm($issuer),
            'eventDataProvider' => $eventDataProvider,
            'importantEventDataProvider' => $importantEventDataProvider,
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

    public function actionRenewAddress($id): Response
    {
        $this->apiAddressInfoFactory->createOrUpdate(Issuer::getOneById($id));

        return $this->redirect(['issuer/view', 'id' => $id]);
    }

    public function actionRenewTypeOfActivity($id): Response
    {
        $this->apiTypeOfActivityFactory->createOrUpdate(Issuer::getOneById($id));

        return $this->redirect(['issuer/view', 'id' => $id]);
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


    public function actionEditDescription(int $issuerId): Response
    {
        $issuer = Issuer::getOneById($issuerId);
        $form = new IssuerDescriptionEditForm();

        $post = Yii::$app->request->post();
        if ($form->load($post) && $form->validate()) {
            $issuer->description = $form->description;
            $issuer->save();

            return $this->redirect(['issuer/view', 'id' => $issuer->id]);
        }

        Yii::$app->session->addFlash('error', 'Ошибка при сохранении описания.');

        return $this->redirect(['issuer/view', 'id' => $issuer->id]);
    }

    public function actionUpdateIssuerInfo($id): Response
    {
        $issuer = Issuer::getOneById($id);
        $this->apiIssuerInfoAndSharesFactory->update($issuer);

        return $this->redirect(['view', 'id' => $issuer->id]);
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