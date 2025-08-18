<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Issuer\EmployeeAmount\EmployeeAmountSearchForm;
use src\Action\Issuer\Event\IssuerEventSearchForm;
use src\Action\Issuer\IssuerCreateForm;
use src\Action\Issuer\IssuerDescriptionEditForm;
use src\Action\Issuer\IssuerSearchForm;
use src\Entity\Issuer\AdditionalInfo\ApiLegatCommonInfoFactory;
use src\Entity\Issuer\ApiIssuerInfoAndSharesFactory;
use src\Entity\Issuer\EmployeeAmount\EmployeeAmountRecord;
use src\Entity\Issuer\EmployeeAmount\EmployeeAmountRecordFactory;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\IssuerAutomaticLegatFactory;
use src\Entity\Issuer\IssuerEvent\BulkIssuerEventFactory;
use src\Entity\Issuer\IssuerFactory;
use src\Entity\Issuer\TypeOfActivity\ApiTypeOfActivityFactory;
use src\Integration\Legat\CommonIssuerInfoFetcherInterface;
use src\Integration\Legat\EmployeeAmountFetcherInterface;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class IssuerController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        private IssuerFactory $factory,
        private ApiTypeOfActivityFactory $apiTypeOfActivityFactory,
        private ApiIssuerInfoAndSharesFactory $apiIssuerInfoAndSharesFactory,
        private BulkIssuerEventFactory $bulkIssuerEventFactory,
        private ApiLegatCommonInfoFactory $apiLegatCommonInfoFactory,
        private CommonIssuerInfoFetcherInterface $commonIssuerInfoFetcher,
        private EmployeeAmountFetcherInterface $employeeAmountFetcher,
        private EmployeeAmountRecordFactory $employeeAmountRecordFactory,
        private IssuerAutomaticLegatFactory $issuerAutomaticLegatFactory,

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
                    'renew-type-of-activity',
                    'edit-description',
                    'update-issuer-info',
                    'create',
                    'renew-legat-issuer-info',
                    'renew-employee-amount',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'renew-business-rating',
                            'renew-esg-rating',
                            'renew-issuer-events',
                            'renew-unreliable-supplier',
                            'renew-type-of-activity',
                            'edit-description',
                            'update-issuer-info',
                            'create',
                            'renew-legat-issuer-info',
                            'renew-employee-amount',
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

    public function actionRenewEmployeeAmount($id): Response
    {
        $issuer = Issuer::getOneById($id);
        $dto = $this->employeeAmountFetcher->getEmployeeAmount($issuer->pid);
        $this->employeeAmountRecordFactory->createMany($issuer, $dto);

        return $this->redirect(['issuer/view', 'id' => $issuer->id]);
    }

    public function actionRenewLegatIssuerInfo($id): Response
    {
        $issuer = Issuer::getOneById($id);

        $dto = $this->commonIssuerInfoFetcher->getCommonInfo($issuer->getPid());
        $this->apiLegatCommonInfoFactory->update($issuer, $dto);

        return $this->redirect(['issuer/view', 'id' => $issuer->id]);
    }


    public function actionView($id): string
    {
        $issuer = Issuer::getOneById($id);

        $searchForm = new IssuerEventSearchForm();
        $eventDataProvider = $searchForm->search($issuer, Yii::$app->request->queryParams);
        $importantEventDataProvider = $searchForm->searchImportant($issuer);

        $employeeSearchForm = new EmployeeAmountSearchForm();
        $employeeAmountDataProvider = $employeeSearchForm->search($issuer, Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $issuer,

            'searchForm' => $searchForm,
            'descriptionEditForm' => new IssuerDescriptionEditForm($issuer),
            'eventDataProvider' => $eventDataProvider,
            'importantEventDataProvider' => $importantEventDataProvider,
            'employeeAmountDataProvider' => $employeeAmountDataProvider,
        ]);
    }

    public function actionRenewIssuerEvents($id): Response
    {
        $issuer = Issuer::getOneById($id);
        $this->bulkIssuerEventFactory->update($issuer->pid);

        return $this->redirect(['issuer/view', 'id' => $issuer->id]);
    }

    public function actionRenewTypeOfActivity($id): Response
    {
        $this->apiTypeOfActivityFactory->createOrUpdate(Issuer::getOneById($id));

        return $this->redirect(['issuer/view', 'id' => $id]);
    }

    public function actionCreate(): Response
    {
        $form = new IssuerCreateForm();


        if (Yii::$app->request->isPost && $post = Yii::$app->request->post()) {
            if (isset($post['simple']) && $form->load($post) && $form->validate()) {
                $issuer = $this->factory->createOrUpdate($form);
            } elseif (isset($post['complex']) && $form->load($post) && $form->validate()) {
                $issuer = $this->issuerAutomaticLegatFactory->createOrUpdate($form);
            } else {
                throw new BadRequestHttpException("Неверный запрос на заполнение.");
            }

            return $this->redirect(['issuer/view', 'id' => $issuer->id]);
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