<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Issuer\IssuerCreateForm;
use src\Action\Issuer\IssuerSearchForm;
use src\Entity\Issuer\Issuer;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

class IssuerController extends BaseController
{
    public $layout = 'main_borderless';

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

    public function actionCreate(): Response
    {
        $form = new IssuerCreateForm();
        $post = Yii::$app->request->post();

        if ($form->load($post) && $form->validate()) {
            Issuer::make($form);
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