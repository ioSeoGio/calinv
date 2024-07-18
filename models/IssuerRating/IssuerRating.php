<?php

namespace app\models\IssuerRating;

use app\models\IssuerRating\SimpleCriteria\IssuerIndicatorGrowth;
use common\Database\BaseActiveRecord;
use common\Database\EmbeddedMany;
use src\IssuerRatingCalculator\SimpleCriteriaCalculator\IndicatorGrowthCalculator;

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
            'indicator',
            'indicatorGrowth',
        ];
    }

    public function rules(): array
    {
        return [
            [[
                'issuer',
                'bikScore',
                'indicator',
                'indicatorGrowth',
            ], 'required'],
        ];
    }
}
