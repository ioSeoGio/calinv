<?php

namespace src\ViewHelper\Icons\IssuerIcon\Button;

use lib\FrontendHelper\Icon;
use src\Entity\Issuer\Issuer;
use src\Entity\User\UserRole;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class ToggleVisibilityIconPrinter
{
    public static function print(Issuer $issuer): string
    {
        if (!Yii::$app->user->can(UserRole::admin->value)) {
            return '';
        }

        if ($issuer->isVisible) {
            return  Html::a(
                Icon::print('bi bi-eye'),
                Url::to(['/issuer/toggle-visibility', 'id' => $issuer->id]),
                [
                    'class' => 'btn btn-sm btn-outline-success me-1',
                    'title' => 'Эмитент показывается пользователям. Нажмите, чтобы скрыть.'
                ]
            );
        }

        return Html::a(
            Icon::print('bi bi-eye-slash'),
            Url::to(['/issuer/toggle-visibility', 'id' => $issuer->id]),
            [
                'class' => 'btn btn-sm btn-outline-danger me-1',
                'title' => 'Эмитент скрыт. Нажмите, чтобы отображать пользователям.'
            ]
        );
    }
}
