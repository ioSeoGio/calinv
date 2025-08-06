<?php

namespace app\controllers;

use lib\BaseController;
use lib\FlashType;
use src\Action\Auth\LoginForm;
use src\Action\Auth\SignUpForm;
use src\Entity\User\UserSignupFactory;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

class AuthController extends BaseController
{
    public function __construct(
        $id,
        $module,
        private UserSignupFactory $userSignupFactory,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'login',
                    'logout',
                    'sign-up',
                    'forgot-password',
                ],
                'rules' => [
                    [
                        'actions' => ['login', 'sign-up', 'forgot-password'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

	public function actionSignup(): Response|string
    {
		$form = new SignupForm();

		if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $user = $this->userSignupFactory->signUp($form);
            Yii::$app->user->login($user, 3600*24*30);

			return $this->redirect(['/']);
		}

		return $this->render('sign-up', [
			'model' => $form,
		]);
	}

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

    public function actionForgotPassword(): Response
    {

        Yii::$app->session->addFlash(FlashType::success->value, 'Новый пароль отправлен на почту, указанную вами.');

        return $this->refresh();
    }
}
