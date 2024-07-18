<?php

namespace common;

use yii\web\Controller;

abstract class BaseController extends Controller
{
    public function actions(): array
    {
        return array_merge(
            [
                'error' => [
                    'class' => 'yii\web\ErrorAction',
                ],
            ],
            parent::actions(),
        );
    }
}
