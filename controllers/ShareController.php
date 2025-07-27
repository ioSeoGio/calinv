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
        $shareSearchForm->isActive = true;
        $shareDataProvider = $shareSearchForm->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'shareCreateForm' => new ShareCreateForm(),
            'shareSearchForm' => $shareSearchForm,
            'shareDataProvider' => $shareDataProvider,
        ]);
    }

    public function actionAllShares(): string
    {
        $shareSearchForm = new ShareSearchForm();
        $shareSearchForm->isActive = false;
        $shareDataProvider = $shareSearchForm->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'shareCreateForm' => new ShareCreateForm(),
            'shareSearchForm' => $shareSearchForm,
            'shareDataProvider' => $shareDataProvider,
        ]);
    }

    public function actionToggleModeration(int $issuerId): Response
    {
        $issuer = Issuer::getOneById($issuerId);

        if ($issuer->dateShareInfoModerated !== null) {
            $issuer->markShareInfoNotModerated();
        } else {
            $issuer->dateShareInfoModerated = new \DateTimeImmutable();
        }
        $issuer->save();

        return $this->redirect(Yii::$app->request->referrer);
    }
}