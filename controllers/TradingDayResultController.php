<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\TradingDayResult\LastTradingDayResultSearch;
use src\Action\TradingDayResult\TradingDayResultSearch;
use Yii;
use yii\filters\AccessControl;

class TradingDayResultController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                        ],
                        'allow' => true,
                        'roles' => ['@', '?'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $search = new LastTradingDayResultSearch();
        $dataProvider = $search->search(new \DateTimeImmutable(), Yii::$app->request->queryParams);
        $lastAvailableDay = $search->getLastAvailableDay();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'lastAvailableDay' => $lastAvailableDay,
            'searchModel' => TradingDayResultSearch::fill(Yii::$app->request->queryParams),
        ]);
    }
}
