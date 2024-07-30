<?php

namespace app\models\IssuerRating;

use app\models\IssuerRating\SimpleCriteria\IssuerIndicatorGrowth;
use common\Database\BaseActiveRecord;
use common\Database\EmbeddedMany;

#[EmbeddedMany(propertyName: 'indicator', objectClass: IssuerIndicator::class)]
#[EmbeddedMany(propertyName: 'indicatorGrowth', objectClass: IssuerIndicatorGrowth::class)]
class IssuerRating extends BaseActiveRecord
{
    public static function tableName(): string
    {
        return 'issuer_ratings';
    }

    public function attributes(): array
    {
        return [
            '_id',
            'issuer',
            'bikScore',
            'shareAmount',
            'indicator',
            'indicatorGrowth',
            'k1_standard',
            'k2_standard',
            'k3_standard',
            'k4_standard',
        ];
    }

    public function rules(): array
    {
        return [
            [[
                'issuer',
                'bikScore',
                'shareAmount',
                'indicator',
                'indicatorGrowth',
                'k1_standard',
                'k2_standard',
                'k3_standard',
            ], 'required'],
            ['issuer', 'unique'],
            [[
                'k1_standard',
                'k2_standard',
                'k3_standard',
                'k4_standard',
            ], 'double'],
        ];
    }

    public function getIndicator(): array
    {
        return $this->indicator;
    }

    public function indicatorGrowth(): array
    {
        return $this->indicatorGrowth;
    }

    public function getAverageGrowth(): array
    {
        $averages = [];
        $counter = 0;
        foreach ($this->indicatorGrowth() as $indicatorGrowth) {
            $averages['Короткие активы'] += $indicatorGrowth['shortAssetGrowth'];
            $averages['Длинные активы'] += $indicatorGrowth['longAssetGrowth'];
            $averages['Капитал'] += $indicatorGrowth['capitalGrowth'];
            $averages['Короткие долги'] += $indicatorGrowth['shortLiabilityGrowth'];
            $averages['Длинные долги'] += $indicatorGrowth['longLiabilityGrowth'];
            $averages['Прибыль'] += $indicatorGrowth['profitGrowth'];
            $counter++;
        }

        $averages['Короткие активы'] /= $counter;
        $averages['Длинные активы'] /= $counter;
        $averages['Капитал'] /= $counter;
        $averages['Короткие долги'] /= $counter;
        $averages['Длинные долги'] /= $counter;
        $averages['Прибыль'] /= $counter;

        return $averages;
    }

    public function getMinimumGrowth(): array
    {
        $minimum = [];
        foreach ($this->indicatorGrowth() as $indicatorGrowth) {
            $minimum['Короткие активы'] = min($minimum['Короткие активы'] ?? PHP_FLOAT_MAX, $indicatorGrowth['shortAssetGrowth']);
            $minimum['Длинные активы'] = min($minimum['Длинные активы'] ?? PHP_FLOAT_MAX, $indicatorGrowth['longAssetGrowth']);
            $minimum['Капитал'] = min($minimum['Капитал'] ?? PHP_FLOAT_MAX, $indicatorGrowth['capitalGrowth']);
            $minimum['Короткие долги'] = min($minimum['Короткие долги'] ?? PHP_FLOAT_MAX, $indicatorGrowth['shortLiabilityGrowth']);
            $minimum['Длинные долги'] = min($minimum['Длинные долги'] ?? PHP_FLOAT_MAX, $indicatorGrowth['longLiabilityGrowth']);
            $minimum['Прибыль'] = min($minimum['Прибыль'] ?? PHP_FLOAT_MAX, $indicatorGrowth['profitGrowth']);
        }

        return $minimum;
    }
}
