<?php

namespace app\models\IssuerRating\SimpleCriteria;

use yii\base\Model;

class IssuerIndicatorGrowth extends Model
{
    public function __construct(
        float $shortAssetGrowth,
        float $longAssetGrowth,
        float $capitalGrowth,
        float $shortLiabilityGrowth,
        float $longLiabilityGrowth,
        float $profitGrowth,
        \DateTimeImmutable $dateFrom,
        \DateTimeImmutable $dateTo,
    ) {
        parent::__construct();
        $this->load([
            'shortAssetGrowth' => $shortAssetGrowth,
            'longAssetGrowth' => $longAssetGrowth,
            'capitalGrowth' => $capitalGrowth,
            'shortLiabilityGrowth' => $shortLiabilityGrowth,
            'longLiabilityGrowth' => $longLiabilityGrowth,
            'profitGrowth' => $profitGrowth,

            'dateFrom' => $dateFrom->format(DATE_ATOM),
            'dateTo' => $dateTo->format(DATE_ATOM),
        ], '');
    }

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
                'dateTo'
            ], 'required'],
            [[
                'shortAssetGrowth',
                'longAssetGrowth',
                'capitalGrowth',
                'shortLiabilityGrowth',
                'longLiabilityGrowth',
                'profitGrowth'
            ], 'double'],
        ];
    }
}
