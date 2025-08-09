<?php

namespace app\controllers;

use lib\BaseController;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Entity\Share\ShareRegisterNumber;
use src\Integration\Bcse\ShareInfo\BcseShareInfoFetcher;
use yii\filters\AccessControl;

class DevController extends BaseController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        private BcseShareInfoFetcher $bcseShareInfoFetcher,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $r = $this->bcseShareInfoFetcher->get(new PayerIdentificationNumber('692041378'), new ShareRegisterNumber('6-404-01-15990'));
        dd($r);
    }
}