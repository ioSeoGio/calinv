<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Portfolio\PortfolioSearch;
use Yii;

class PortfolioController extends BaseController
{
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