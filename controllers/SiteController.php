<?php

namespace app\controllers;

use lib\BaseController;
use yii\filters\AccessControl;

class SiteController extends BaseController
{
    public function __construct(
        $id,
        $module,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'faq',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'faq',
                        ],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    public function actionFaq(): string
    {
        return $this->render('faq', [
        ]);
    }
}