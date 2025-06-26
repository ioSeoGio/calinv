<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Auth\LoginForm;
use Yii;
use yii\web\Response;

class AuthController extends BaseController
{
	public function actionLogin(): Response|string
    {
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();

		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		}

		$model->password = '';
		return $this->render('login', [
			'model' => $model,
		]);
	}

	public function actionLogout(): Response
    {
		Yii::$app->user->logout();

		return $this->goHome();
	}
}
