<?php

namespace app\controllers\Portfolio;

use app\controllers\Portfolio\Form\PersonalShareForm;
use app\controllers\Portfolio\Search\PersonalShareSearchForm;
use app\models\Portfolio\Factory\PersonalShareFactory;
use common\BaseController;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

class PersonalShareController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,

        private PersonalShareFactory $personalShareFactory,

        $config = [],
    ) {
        $this->setViewPath('@app/views/portfolio');
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $sharesSearchForm = new PersonalShareSearchForm();
        $sharesDataProvider = $sharesSearchForm->search(Yii::$app->request->queryParams);

        return $this->render('personal_share_index', [
            'personalShareForm' => new PersonalShareForm(),
            'personalShareSearchForm' => $sharesSearchForm,
            'personalShareDataProvider' => $sharesDataProvider,
        ]);
    }

    public function actionCreate(): mixed
    {
        if ($post = Yii::$app->request->post()) {
            $form = new PersonalShareForm();

            if ($form->load($post) && $form->validate()) {
                $share = $this->personalShareFactory->create($form);
                $share->save();

                return $this->redirect(['/Portfolio/personal-share/index']);
            }
        }

        return $this->redirect(['/Portfolio/personal-share/index']);
    }

    public function actionValidate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($post = Yii::$app->request->post()) {
            $form = new PersonalShareForm();
            $form->load($post);

            $r = ActiveForm::validate($form);
            return $r;
        }

        return [];
    }
}
