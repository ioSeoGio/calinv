<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Portfolio\PortfolioSearch;
use src\Entity\User\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

class PortfolioController extends BaseController
{
    public function __construct(
        $id,
        $module,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'toggle-visibility',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'toggle-visibility',
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionToggleVisibility(int $id): Response
    {
        $user = User::getOneById($id);
        $user->isPortfolioVisible = !$user->isPortfolioVisible;
        $user->save();

        return $this->redirect(Yii::$app->request->referrer ?: ['/portfolio/search']);
    }

    public function actionSearch(): string
    {
        $searchModel = new PortfolioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('portfolio_search', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}