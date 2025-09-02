<?php

namespace src\ViewHelper\Shit;

use kartik\select2\Select2;
use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfo;
use src\Entity\Issuer\CreditRating\CreditRatingInfo;
use src\Entity\Issuer\EsgRating\EsgRatingInfo;
use src\Entity\Issuer\Issuer;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class RatingSelectIssuerDropdown
{
    public static function render(BusinessReputationInfo|EsgRatingInfo|CreditRatingInfo $model, array $issuers): string
    {
        return Select2::widget([
            'model' => $model,
            'attribute' => 'issuerId',
            'data' => ArrayHelper::map($issuers,
                from: fn (Issuer $model) => (string) $model->id,
                to: fn (Issuer $model) => $model->name,
            ),
            'options' => [
                'placeholder' => 'Выберите эмитента',
                'class' => 'form-control rating-select-issuer-dropdown',
                'id' => 'rating-select-issuer-dropdown-' . $model->id,
                'data-rating-id' => $model->id,
            ],
            'pluginOptions' => [
                'theme' => Select2::THEME_KRAJEE_BS5,
                'dropdownCssClass' => 'bg-dark text-white', // Дополнительные классы для dropdown
            ],
        ]);
    }
}