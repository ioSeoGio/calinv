<?php

namespace app\controllers\IssuerRatingCalculator;

use yii\base\Model;

class CalculateSimpleForm extends Model
{
    public string $issuer = 'Amazon';
    public string $bikScore = 'AA+';

    public float $k1_standard = 1;
    public float $k2_standard = 0.2;
    public float $k3_standard = 1.5;
    public null|string|float $k4_standard = null;

    public function rules(): array
    {
        return [
            [[
                'issuer',
                'bikScore',
                'k1_standard',
                'k2_standard',
                'k3_standard',
            ], 'required', 'message' => 'Заполните.'],
            [['issuer', 'bikScore'], 'string'],
            [[
                'k1_standard',
                'k2_standard',
                'k3_standard',
                'k4_standard',
            ], 'double'],
            ['k4_standard', function ($value) {
                return $value === '' ? null : $value;
            }]
        ];
    }
}
