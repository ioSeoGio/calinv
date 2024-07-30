<?php

namespace app\controllers\Portfolio;

use app\controllers\Portfolio\Form\PersonalBondForm;
use app\controllers\Portfolio\Search\PersonalBondSearchForm;
use app\models\Portfolio\Factory\PersonalBondFactory;
use common\BaseController;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

class PersonalBondController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,

        private PersonalBondFactory $personalBondFactory,

        $config = [],
    ) {
        $this->setViewPath('@app/views/portfolio');
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $bondsSearchForm = new PersonalBondSearchForm();
        $bondsDataProvider = $bondsSearchForm->search(Yii::$app->request->queryParams);

        return $this->render('personal_bond_index', [
            'personalBondForm' => new PersonalBondForm(),
            'personalBondSearchForm' => $bondsSearchForm,
            'personalBondDataProvider' => $bondsDataProvider,
        ]);
    }

    public function actionCreate(): mixed
    {
        if ($post = Yii::$app->request->post()) {
            $form = new PersonalBondForm();

            if ($form->load($post) && $form->validate()) {
                $share = $this->personalBondFactory->create($form);
                $share->save();

                return $this->redirect(['/Portfolio/personal-bond/index']);
            }
        }

        return $this->redirect(['/Portfolio/personal-bond/index']);
    }

    public function actionValidate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($post = Yii::$app->request->post()) {
            $form = new PersonalBondForm();
            $form->load($post);

            $r = ActiveForm::validate($form);
            return $r;
        }

        return [];
    }
}
