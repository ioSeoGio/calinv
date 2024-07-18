<?php

namespace app\controllers\IssuerRatingCalculator;

use app\models\IssuerRating\Factory\IssuerRatingFactory;
use app\models\IssuerRating\IssuerRating;
use common\BaseController;
use Yii;

class CalculatorController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,

        public IssuerRatingFactory $issuerRatingFactory,

        $config = [],
    ) {
        $this->setViewPath('@app/views/calculator');
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        return $this->render('index', [
            'model' => new CalculateForm(),
        ]);
    }

    public function actionCalculate(): mixed
    {
        $model = new CalculateForm();

        dd(Yii::$app->request->post());
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $rating = $this->issuerRatingFactory->create($model);
                $rating->save();

                return $this->redirect(['index']);
            }

            return $this->render('index', [
                'model' => $model,
            ]);
        }

        return $this->redirect(['index']);
    }
}
