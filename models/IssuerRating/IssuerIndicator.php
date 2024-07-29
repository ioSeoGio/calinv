<?php

namespace app\models\IssuerRating;

use yii\base\Model;

class IssuerIndicator extends Model
{
    public float $shortAsset;
    public float $longAsset;
    public float $capital;
    public float $shortLiability;
    public float $longLiability;
    public float $profit;
    public float $k1;
    public float $k2;
    public float $k3;
    public float $PE;
    public string $date;

    public function rules(): array
    {
        return [
            [[
                'shortAsset',
                'longAsset',
                'capital',
                'shortLiability',
                'longLiability',
                'profit',
                'k1',
                'k2',
                'k3',
                'PE',
                'date',
            ], 'required'],
            [[
                'shortAsset',
                'longAsset',
                'capital',
                'shortLiability',
                'longLiability',
                'profit',
                'k1',
                'k2',
                'k3',
                'PE',
            ], 'double'],
        ];
    }

    public function getDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->date);
    }
}
