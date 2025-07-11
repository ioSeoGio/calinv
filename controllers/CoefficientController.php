<?php

namespace app\controllers;

use lib\BaseController;
use src\Entity\Issuer\Issuer;

class CoefficientController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionView(int $issuerId): string
    {
        $issuer = Issuer::getOneById($issuerId);

        return $this->render('view', [
            'model' => $issuer
        ]);
    }
}