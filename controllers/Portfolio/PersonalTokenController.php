<?php

namespace app\controllers\Portfolio;

use app\controllers\Portfolio\Form\PersonalTokenForm;
use app\controllers\Portfolio\Search\PersonalTokenSearchForm;
use app\models\Portfolio\Factory\PersonalTokenFactory;
use common\BaseController;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

class PersonalTokenController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,

        private PersonalTokenFactory $personalTokenFactory,

        $config = [],
    ) {
        $this->setViewPath('@app/views/portfolio');
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $tokensSearchForm = new PersonalTokenSearchForm();
        $tokensDataProvider = $tokensSearchForm->search(Yii::$app->request->queryParams);

        return $this->render('personal_token_index', [
            'personalTokenForm' => new PersonalTokenForm(),
            'personalTokenSearchForm' => $tokensSearchForm,
            'personalTokenDataProvider' => $tokensDataProvider,
        ]);
    }

    public function actionCreate(): mixed
    {
        if ($post = Yii::$app->request->post()) {
            $form = new PersonalTokenForm();

            if ($form->load($post) && $form->validate()) {
                $share = $this->personalTokenFactory->create($form);
                $share->save();

                return $this->redirect(['/Portfolio/personal-token/index']);
            }
        }

        return $this->redirect(['/Portfolio/personal-token/index']);
    }

    public function actionValidate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($post = Yii::$app->request->post()) {
            $form = new PersonalTokenForm();
            $form->load($post);

            $r = ActiveForm::validate($form);
            return $r;
        }

        return [];
    }
}
