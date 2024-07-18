<?php

namespace app\controllers\IssuerRatingCalculator;

use yii\base\Model;

class CalculateForm extends Model
{
    public string $issuer = 'Amazon';
    public string $bikScore = 'AA+';
    public float $longAssetPerYear = 123;
    public array $longAssetPerYearData = [];
    public float $shortAssetPerYear = 222;
    public array $shortAssetPerYearData = [];

    public float $longLiabilityPerYear = 1;
    public array $longLiabilityPerYearData = [];
    public float $shortLiabilityPerYear = 324;
    public array $shortLiabilityPerYearData = [];


    public float $profitPerYear = 432;
    public array $profitPerYearData = [];

    public float $capitalPerYear = 235;
    public array $capitalPerYearData = [];

    public float $k1_standard = 1;
    public float $k2_standard = 0.2;
    public float $k3_standard = 1.5;

    public function rules(): array
    {
        return [
            [[
                'issuer',
                'bikScore',
                'shortAssetPerYear',
                'longAssetPerYear',
                'shortLiabilityPerYear',
                'longLiabilityPerYear',
                'shortProfitPerYear',
                'longProfitPerYear',
                'shortCapitalPerYear',
                'longCapitalPerYear',
            ], 'required', 'message' => 'Заполните.'],
            [['issuer', 'bikScore'], 'string'],
//            [['assetPerYear', 'liabilityPerYear', 'profitPerYear', 'capitalPerYear'], 'each', 'rule' => 'float'],
        ];
    }

    public function load($data, $formName = null)
    {
        foreach ($data as $key => $value) {
            if (is_array($value) && !is_array($this->$key)) {
                $this->{$key.'Data'} = $value;
                continue;
            }
            $this->$key = $value;
        }
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {

        return parent::validate($attributeNames, $clearErrors);
    }
}
