<?php

namespace src\ViewHelper\IssuerIcon;

use lib\FrontendHelper\Icon;
use src\Entity\Issuer\Issuer;
use src\Entity\User\UserRole;
use src\ViewHelper\IssuerIcon\Share\IssuerShareFullnessStateIconPrinter;
use src\ViewHelper\IssuerIcon\Share\IssuerShareInfoModeratedIconPrinter;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class IssuerStateIconsPrinter
{
    public static function printMany(Issuer $model): string
    {
        $result = '';

        if (Yii::$app->user->can(UserRole::admin->value)) {
            if ($model->isVisible) {
                $result .= Html::a(
                    Icon::print('bi bi-eye'),
                    Url::to(['/issuer/toggle-visibility', 'id' => $model->id]),
                    [
                        'class' => 'btn btn-sm btn-outline-success me-1',
                        'title' => 'Эмитент показывается пользователям. Нажмите, чтобы скрыть.'
                    ]
                );
            } else {
                $result .= Html::a(
                    Icon::print('bi bi-eye-slash'),
                    Url::to(['/issuer/toggle-visibility', 'id' => $model->id]),
                    [
                        'class' => 'btn btn-sm btn-outline-danger me-1',
                        'title' => 'Эмитент скрыт. Нажмите, чтобы отображать пользователям.'
                    ]
                );
            }
        }

        $result .= ''
            . IssuerEmployeeRetiredMoreThanAllowedPercentIconPrinter::print($model)
            . IssuerDebtIconPrinter::print($model)
            . IssuerTaxesDebtIconPrinter::print($model)
            . IssuerBankruptOrLiquidationIconPrinter::print($model)
            . IssuerShareFullnessStateIconPrinter::print($model)
            . IssuerShareInfoModeratedIconPrinter::print($model)
            . IssuerUnreliableSupplierIconPrinter::print($model);

        return $result;
    }
}