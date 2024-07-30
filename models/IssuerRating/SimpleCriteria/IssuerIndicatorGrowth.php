<?php

namespace app\models\IssuerRating\SimpleCriteria;

use yii\base\Model;

class IssuerIndicatorGrowth extends Model
{
    public float $shortAssetGrowth;
    public float $longAssetGrowth;
    public float $capitalGrowth;
    public float $shortLiabilityGrowth;
    public float $longLiabilityGrowth;
    public float $profitGrowth;
    public string $dateFrom;
    public string $dateTo;


    public function rules(): array
    {
        return [
            [[
                'shortAssetGrowth',
                'longAssetGrowth',
                'capitalGrowth',
                'shortLiabilityGrowth',
                'longLiabilityGrowth',
                'profitGrowth',
                'dateFrom',
                'dateTo',
            ], 'required'],
            [[
                'shortAssetGrowth',
                'longAssetGrowth',
                'capitalGrowth',
                'shortLiabilityGrowth',
                'longLiabilityGrowth',
                'profitGrowth',
            ], 'double'],
        ];
    }
}
