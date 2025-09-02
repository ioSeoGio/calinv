<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Share\PersonalShareCreateForm;
use src\Action\Share\PersonalShareSearchForm;
use src\Entity\PersonalShare\PersonalShare;
use src\Entity\User\User;
use src\Entity\User\UserRole;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class PersonalShareController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->setViewPath('@app/views/portfolio');
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
                    'create',
                    'delete',
                ],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'charts'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCharts(?int $userId = null): string
    {
        $user = $userId !== null ? User::getOneById($userId) : Yii::$app->user->identity;

        $sharesSearchForm = new PersonalShareSearchForm();
        $sharesDataProvider = $sharesSearchForm->search($user, Yii::$app->request->queryParams);

        return $this->render('charts', [
            'dataProvider' => $sharesDataProvider,
            'user' => $user,
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $personalShare = PersonalShare::getOneById($id);

        if ($personalShare->user_id !== Yii::$app->user->id && !Yii::$app->user->can(UserRole::admin->value)) {
            throw new ForbiddenHttpException();
        }

        $personalShare->delete();
        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    public function actionIndex(?int $userId = null): string
    {
        $user = $userId !== null
            ? User::getOneById($userId)
            : Yii::$app->user->identity;

        if (
            !Yii::$app->user->can(UserRole::admin->value)
            && (!$user->isPortfolioVisible || !$user->isPortfolioPublic)
            && $userId !== Yii::$app->user->id
        ) {
            throw new ForbiddenHttpException();
        }

        $sharesSearchForm = new PersonalShareSearchForm();
        $sharesDataProvider = $sharesSearchForm->search($user, Yii::$app->request->queryParams);

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