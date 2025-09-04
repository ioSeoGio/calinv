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

    public function actionView(?int $issuerId = null, ?string $unp = null): string
    {
        $issuer = $unp ? Issuer::getOneByPid($unp) : Issuer::getOneById($issuerId);

        return $this->render('view', [
            'model' => $issuer
        ]);
    }
}