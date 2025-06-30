<?php

namespace app\controllers;

use lib\BaseController;
use lib\Exception\UserException\ApiInternalErrorException;
use src\Action\Issuer\IssuerCreateForm;
use src\Action\Issuer\IssuerSearchForm;
use src\Action\Issuer\Rating\BusinessReputationInfoSearch;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\IssuerFactory;
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

    public function actionRating(): string
    {
        $searchForm = new BusinessReputationInfoSearch();
        $dataProvider = $searchForm->search(Yii::$app->request->queryParams);

        return $this->render('issuer_business_rating', [
            'searchForm' => $searchForm,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(): Response
    {
        $form = new IssuerCreateForm();
        $post = Yii::$app->request->post();

        if ($form->load($post) && $form->validate()) {
            $this->factory->create($form);
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