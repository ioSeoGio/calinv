<?php

namespace app\controllers;

use lib\BaseController;
use src\Action\Share\ShareCreateForm;
use src\Action\Share\ShareSearchForm;
use src\Entity\Issuer\Issuer;
use src\Entity\Share\Share;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class ShareController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $shareSearchForm = new ShareSearchForm();
        $shareDataProvider = $shareSearchForm->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'shareCreateForm' => new ShareCreateForm(),
            'shareSearchForm' => $shareSearchForm,
            'shareDataProvider' => $shareDataProvider,
        ]);
    }
}