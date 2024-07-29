<?php

namespace app\controllers\IssuerRatingCalculator;

use yii\base\Model;

class CalculateSimpleForm extends Model
{
    public string $issuer = 'Amazon';
    public string $bikScore = 'AA+';
    public int $shareAmount = 1;

    public float $k1_standard = 1;
    public float $k2_standard = 0.2;
    public float $k3_standard = 1.5;

    public function rules(): array
    {
        return [
            [[
                'issuer',
                'bikScore',
                'shareAmount',
                'k1_standard',
                'k2_standard',
                'k3_standard',
            ], 'required', 'message' => 'Заполните.'],
            [['issuer', 'bikScore'], 'string'],
            [['shareAmount'], 'integer'],
        ];
    }
}
