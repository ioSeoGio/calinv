<?php

namespace app\controllers\FinancialInstrument;

use app\controllers\FinancialInstrument\Form\ShareForm;
use app\controllers\FinancialInstrument\Search\ShareSearchForm;
use app\models\FinancialInstrument\Factory\ShareFactory;
use common\BaseController;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;

class ShareController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,

        private ShareFactory $shareFactory,

        $config = [],
    ) {
        $this->setViewPath('@app/views/financial-instrument');
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $sharesSearchForm = new ShareSearchForm();
        $sharesDataProvider = $sharesSearchForm->search(Yii::$app->request->queryParams);

        return $this->render('share_index', [
            'shareForm' => new ShareForm(),
            'sharesSearchForm' => $sharesSearchForm,
            'sharesDataProvider' => $sharesDataProvider,
        ]);
    }

    public function actionCreate(): mixed
    {
        if ($post = Yii::$app->request->post()) {
            $form = new ShareForm();

            if ($form->load($post) && $form->validate()) {
                $share = $this->shareFactory->create($form);
                $share->save();

                return $this->redirect(['/FinancialInstrument/share/index']);
            }
        }

        return $this->redirect(['/FinancialInstrument/share/index']);
    }

    public function actionValidate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($post = Yii::$app->request->post()) {
            $form = new ShareForm();
            $form->load($post);

            $r = ActiveForm::validate($form);
            return $r;
        }

        return [];
    }
}
