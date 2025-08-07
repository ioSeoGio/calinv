<?php

namespace app\filters;

use src\Entity\User\UserRole;
use Yii;
use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class AdminFilter extends ActionFilter
{
    public function beforeAction($action): bool
    {
        if (Yii::$app->user->isGuest) {
            throw new NotFoundHttpException();
        }

        if (!Yii::$app->user->can(UserRole::admin->value)) {
            throw new NotFoundHttpException();
        }

        return parent::beforeAction($action);
    }
}
