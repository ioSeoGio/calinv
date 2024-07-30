<?php

namespace app\controllers\IssuerRatingCalculator;

use yii\base\Model;

class CalculateIndicatorForm extends Model
{
    public $longAsset = 123;
    public $shortAsset = 222;

    public $longLiability = 1;
    public $shortLiability = 324;

    public $profit = 432;
    public $capital = 235;

    public function rules(): array
    {
        return [
            [[
                'shortAsset',
                'longAsset',
                'shortLiability',
                'longLiability',
                'profit',
                'capital',
            ], 'required', 'message' => 'Заполните.'],
            [[
                'shortAsset',
                'longAsset',
                'shortLiability',
                'longLiability',
                'profit',
                'capital',
            ], 'double'],
        ];
    }
}
