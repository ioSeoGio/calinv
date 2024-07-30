<?php

namespace app\controllers\IssuerRatingCalculator;

use app\models\IssuerRating\Factory\IssuerRatingFactory;
use common\BaseController;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

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
        $searchForm = new IssuerRatingSearchForm();
        $dataProvider = $searchForm->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchForm' => $searchForm,
            'dataProvider' => $dataProvider,

            'simpleForm' => new CalculateSimpleForm(),
            'indicatorForm' => new CalculateIndicatorForm(),
        ]);
    }

    public function actionCalculate(): mixed
    {
        if ($post = Yii::$app->request->post()) {
            $indicatorForms = [];
            foreach ($post['CalculateIndicatorForm'] ?? [] as $formData) {
                $indicatorForms[] = new CalculateIndicatorForm();
            }
            $simpleForm = new CalculateSimpleForm();

            if ($simpleForm->load($post) && CalculateIndicatorForm::loadMultiple($indicatorForms, $post)) {
                $rating = $this->issuerRatingFactory->create($simpleForm, ...$indicatorForms);
                $rating->save();

                return $this->redirect(['index']);
            }
        }

        return $this->redirect(['index']);
    }

    public function actionValidate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($post = Yii::$app->request->post()) {
            $indicatorForms = [];
            foreach ($post['CalculateIndicatorForm'] ?? [] as $formData) {
                $indicatorForms[] = new CalculateIndicatorForm();
            }
            $simpleForm = new CalculateSimpleForm();

            $simpleForm->load($post);
            CalculateIndicatorForm::loadMultiple($indicatorForms, $post);

            $r = array_merge(ActiveForm::validate($simpleForm), ActiveForm::validateMultiple($indicatorForms));
            return $r;
        }
        return [];
    }
}
