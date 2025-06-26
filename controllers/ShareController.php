<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Share\ShareCreateForm;
use src\Action\Share\ShareSearchForm;
use src\Entity\Share\Share;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class ShareController extends BaseController
{
    public $layout = 'main_borderless';

    public function actionIndex(): string
    {
        $shareSearchForm = new ShareSearchForm();
        $shareDataProvider = $shareSearchForm->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'shareCreateForm' => new ShareCreateForm(),
            'shareSearchForm' => $shareSearchForm,
            'shareDataProvider' => $shareDataProvider,
        ]);
    }

    public function actionCreate(): Response
    {
        if ($post = Yii::$app->request->post()) {
            $form = new ShareCreateForm();

            if ($form->load($post) && $form->validate()) {
                Share::make($form);

                return $this->redirect(['index']);
            }
        }

        return $this->redirect(['index']);
    }

    public function actionUpdatePrice(): array|Response
    {
        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException("Only ajax request is allowed");
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $price = Yii::$app->request->post('price');

        $share = Share::findOne($id);
        if ($share === null) {
            return ['success' => false];
        }

        $share->currentPrice = $price;
        if ($share->save()) {
            return ['success' => true];
        }

        return ['success' => false];
    }

    public function actionValidate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($post = Yii::$app->request->post()) {
            $form = new ShareCreateForm();
            $form->load($post);

            $r = ActiveForm::validate($form);
            return $r;
        }

        return [];
    }
}