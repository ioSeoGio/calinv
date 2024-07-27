<?php

namespace app\controllers\FinancialInstrument;

use app\controllers\FinancialInstrument\Form\BondForm;
use app\controllers\FinancialInstrument\Search\BondSearchForm;
use app\models\FinancialInstrument\Factory\BondFactory;
use common\BaseController;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

class BondController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,

        private BondFactory $bondFactory,

        $config = [],
    ) {
        $this->setViewPath('@app/views/financial-instrument');
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $bondsSearchForm = new BondSearchForm();
        $bondsDataProvider = $bondsSearchForm->search(Yii::$app->request->queryParams);

        return $this->render('bond_index', [
            'bondForm' => new BondForm(),
            'bondsSearchForm' => $bondsSearchForm,
            'bondsDataProvider' => $bondsDataProvider,
        ]);
    }

    public function actionCreate(): mixed
    {
        if ($post = Yii::$app->request->post()) {
            $bondForm = new BondForm();

            if ($bondForm->load($post) && $bondForm->validate()) {
                $bond = $this->bondFactory->create($bondForm);
                $bond->save();

                return $this->redirect(['/FinancialInstrument/bond/index']);
            }
        }

        return $this->redirect(['/FinancialInstrument/bond/index']);
    }

    public function actionValidate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($post = Yii::$app->request->post()) {
            $form = new BondForm();
            $form->load($post);

            $r = ActiveForm::validate($form);
            return $r;
        }

        return [];
    }
}
