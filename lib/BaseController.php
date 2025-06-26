<?php

namespace lib;

use yii\web\Controller;
use yii\web\ErrorAction;

abstract class BaseController extends Controller
{
    public function actions(): array
    {
        return array_merge(
            [
                'error' => [
                    'class' => ErrorAction::class,
                ],
            ],
            parent::actions(),
        );
    }
}