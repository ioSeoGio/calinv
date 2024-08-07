<?php

namespace app\controllers;

use app\controllers\Portfolio\Search\PortfolioSearch;
use Yii;
use yii\web\Controller;
use app\models\ProfileForm;
use yii\web\Response;

class ProfileController extends Controller
{
	public function actionIndex(): Response|string
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

	public function actionEdit(): Response|string
    {
		$user = Yii::$app->user->identity;
		$profileForm = new ProfileForm();

		if (Yii::$app->request->isPost) {
			if ($profileForm->load(Yii::$app->request->post()) && $profileForm->validate()) {
				// Обновляем поля профиля
				$user->username = $profileForm->username;
				$user->email = $profileForm->email;

				// Обновляем пароль, если он указан
				if ($profileForm->new_password) {
					$user->setPassword($profileForm->new_password);
				}

				$user->save();
				return $this->redirect(['profile/index']);
			}
		} else {
			$profileForm->username = $user->username;
			$profileForm->email = $user->email;
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
