<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\ProfileForm;

class ProfileController extends Controller
{
	public function actionIndex()
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['auth/login']);
		}

		// Устанавливаем хлебные крошки
		$this->view->params['breadcrumbs'] = [
			'Профиль',
		];

		return $this->render('index', [
			'model' => Yii::$app->user->identity,
		]);
	}

	public function actionEdit()
	{
		$user = Yii::$app->user->identity;
		$profileForm = new ProfileForm();

		if (Yii::$app->request->isPost) {
			if ($profileForm->load(Yii::$app->request->post()) && $profileForm->validate()) {
				// Обновляем поля профиля
				$user->username = $profileForm->username;
				$user->fio = $profileForm->fio;
				$user->email = $profileForm->email;
				$user->phone_number = $profileForm->phone_number;

				// Обновляем пароль, если он указан
				if ($profileForm->new_password) {
					$user->setPassword($profileForm->new_password);
				}

				$user->save();
				return $this->redirect(['profile/index']);
			}
		} else {
			$profileForm->username = $user->username;
			$profileForm->fio = $user->fio;
			$profileForm->email = $user->email;
			$profileForm->phone_number = $user->phone_number;
		}

		// Устанавливаем хлебные крошки
		$this->view->params['breadcrumbs'] = [
			['label' => 'Профиль', 'url' => ['profile/index']],
			'Редактирование профиля',
		];

		return $this->render('edit', [
			'model' => $profileForm,
		]);
	}
}
