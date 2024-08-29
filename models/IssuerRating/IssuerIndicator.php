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
    public ?float $k4 = null;
    public ?float $PE = null;
    public ?float $PB = null;
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
            ], 'double'],
            [[
                'k4',
                'PE',
                'PB',
            ], 'safe'],
        ];
    }

    public function getDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->date);
    }
}
