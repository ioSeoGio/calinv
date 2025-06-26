<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Share\PersonalShareCreateForm;
use src\Action\Share\PersonalShareSearchForm;
use src\Entity\PersonalShare\PersonalShare;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

class PersonalShareController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->setViewPath('@app/views/portfolio');
    }

    public function actionIndex(): string
    {
        $sharesSearchForm = new PersonalShareSearchForm();
        $sharesDataProvider = $sharesSearchForm->search(Yii::$app->request->queryParams);

        return $this->render('personal_share_index', [
            'personalShareCreateForm' => new PersonalShareCreateForm(),
            'personalShareSearchForm' => $sharesSearchForm,
            'personalShareDataProvider' => $sharesDataProvider,
        ]);
    }

    public function actionCreate(): Response
    {
        if ($post = Yii::$app->request->post()) {
            $form = new PersonalShareCreateForm();

            if ($form->load($post) && $form->validate()) {
                PersonalShare::make($form);

                return $this->redirect(['/personal-share/index']);
            }
        }

        return $this->redirect(['/personal-share/index']);
    }

    public function actionValidate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($post = Yii::$app->request->post()) {
            $form = new PersonalShareCreateForm();
            $form->load($post);

            $r = ActiveForm::validate($form);
            return $r;
        }

        return [];
    }
}