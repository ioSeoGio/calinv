<?php
namespace app\controllers;

use src\Action\Profile\ProfileForm;
use src\Entity\User\User;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

class ProfileController extends Controller
{
	public function actionIndex(): Response|string
    {
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['/auth/login']);
		}

		return $this->render('index', [
			'model' => Yii::$app->user->identity,
		]);
	}

	public function actionEdit(): Response|string
    {
        if (Yii::$app->user->isGuest) {
			return $this->redirect(['/auth/login']);
		}

		$user = Yii::$app->user->identity;
		$profileForm = new ProfileForm($user);

        if ($profileForm->load(Yii::$app->request->post()) && $profileForm->validate()) {
            $user->updateProfile(
                username: $profileForm->username,
                email: $profileForm->email,
                newPassword: $profileForm->newPassword,
            );
            $user->save();

            return $this->redirect(['profile/index']);
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

    public function actionToggleVisibility(): array
    {
        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException("Only ajax requests are allowed");
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Yii::$app->user->identity;

        $visible = Yii::$app->request->post('visible', false);
        $user->isPortfolioPublic = $visible;

        if ($user->save()) {
            return [
                'success' => true,
                'message' => 'Статус портфеля успешно обновлен.',
            ];
        }

        return [
            'success' => false,
            'message' => 'Ошибка при сохранении настроек портфеля.',
        ];
    }
}
