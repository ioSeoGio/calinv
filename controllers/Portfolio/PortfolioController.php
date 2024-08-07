<?php

namespace app\controllers\Portfolio;

use app\controllers\Portfolio\Search\PortfolioSearch;
use common\BaseController;
use Yii;

class PortfolioController extends BaseController
{
    public function __construct(
        $id,
        $module,

        $config = [],
    ) {
        $this->setViewPath('@app/views/portfolio');
        parent::__construct($id, $module, $config);
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
