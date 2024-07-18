<?php

namespace app\models\IssuerRating\SimpleCriteria;

use yii\base\Model;

class CalculatedCoefficient extends Model
{
    public function __construct(
        float $k1,
        float $k2,
        float $k3,
        \DateTimeImmutable $date,
    ) {
        parent::__construct();
        $this->load([
            'k1' => $k1,
            'k2' => $k2,
            'k3' => $k3,

            'date' => $date->format(DATE_ATOM),
        ], '');
    }

    public function rules(): array
    {
        return [
            [[
                'k1',
                'k2',
                'k3',
                'dateFrom',
                'dateTo'
            ], 'required'],
            [[
                'k1',
                'k2',
                'k3',
            ], 'double'],
        ];
    }
}
