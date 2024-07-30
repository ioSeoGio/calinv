<?php

namespace app\controllers\FinancialInstrument;

use app\controllers\FinancialInstrument\Form\ShareForm;
use app\controllers\FinancialInstrument\Form\TokenForm;
use app\controllers\FinancialInstrument\Search\TokenSearchForm;
use app\models\FinancialInstrument\Factory\ShareFactory;
use app\models\FinancialInstrument\Factory\TokenFactory;
use common\BaseController;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

class TokenController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,

        private TokenFactory $tokenFactory,

        $config = [],
    ) {
        $this->setViewPath('@app/views/financial-instrument');
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $tokensSearchForm = new TokenSearchForm();
        $tokensDataProvider = $tokensSearchForm->search(Yii::$app->request->queryParams);

        return $this->render('token_index', [
            'tokenForm' => new TokenForm(),
            'tokensSearchForm' => $tokensSearchForm,
            'tokensDataProvider' => $tokensDataProvider,
        ]);
    }

    public function actionCreate(): mixed
    {
        if ($post = Yii::$app->request->post()) {
            $form = new TokenForm();

            if ($form->load($post) && $form->validate()) {
                $token = $this->tokenFactory->create($form);
                $token->save();

                return $this->redirect(['/FinancialInstrument/token/index']);
            }
        }

        return $this->redirect(['/FinancialInstrument/token/index']);
    }

    public function actionValidate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($post = Yii::$app->request->post()) {
            $form = new TokenForm();
            $form->load($post);

            $r = ActiveForm::validate($form);
            return $r;
        }

        return [];
    }
}
