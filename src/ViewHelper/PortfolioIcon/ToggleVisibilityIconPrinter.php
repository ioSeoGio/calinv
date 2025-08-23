<?php

namespace src\ViewHelper\PortfolioIcon;

use lib\FrontendHelper\Icon;
use src\Entity\User\User;
use src\Entity\User\UserRole;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class ToggleVisibilityIconPrinter
{
    public static function print(User $user): string
    {
        if (!Yii::$app->user->can(UserRole::admin->value)) {
            return '';
        }

        if ($user->isPortfolioVisible) {
            return  Html::a(
                Icon::print('bi bi-eye'),
                Url::to(['/portfolio/toggle-visibility', 'id' => $user->id]),
                [
                    'class' => 'btn btn-sm btn-outline-success me-1',
                    'title' => 'Портфель показывается пользователям. Нажмите, чтобы скрыть.'
                ]
            );
        }

        return Html::a(
            Icon::print('bi bi-eye-slash'),
            Url::to(['/portfolio/toggle-visibility', 'id' => $user->id]),
            [
                'class' => 'btn btn-sm btn-outline-danger me-1',
                'title' => 'Портфель скрыт. Нажмите, чтобы отображать пользователям.'
            ]
        );
    }
}
