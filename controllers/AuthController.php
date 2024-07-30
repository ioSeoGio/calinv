<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\SignupForm;
use app\models\LoginForm;

class AuthController extends Controller
{
	public function actionSignup()
	{
		$model = new SignupForm();

		if ($model->load(Yii::$app->request->post()) && $model->signup()) {
			return $this->redirect(['login']);
		}

		return $this->render('signup', [
			'model' => $model,
		]);
	}

	public function actionLogin()
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

	public function actionLogout()
	{
		Yii::$app->user->logout();
		return $this->goHome();
	}
}
