<?php

namespace app\controllers;

use common\BaseController;
use app\models\User;
use Yii;

class SiteController extends BaseController
{
    public function __construct(
        $id,
        $module,

        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }
}
