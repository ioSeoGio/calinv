<?php

namespace app\models\IssuerRating;

use common\Model\BaseModel;
use src\IssuerRatingCalculator\SimpleCriteriaCalculator\CoefficientCalculator;
use yii\base\Model;

class IssuerIndicator extends Model
{
    public string $date;
    public float $k1;
    public float $k2;
    public float $k3;

    public function __construct(
        public float $shortAsset,
        public float $longAsset,
        public float $capital,
        public float $shortLiability,
        public float $longLiability,
        public float $profit,
        \DateTimeImmutable $date,
    ) {
        parent::__construct();
        $this->load([
            'shortAsset' => $shortAsset,
            'longAsset' => $longAsset,
            'capital' => $capital,
            'shortLiability' => $shortLiability,
            'logLiability' => $longLiability,
            'profit' => $profit,
            'k1' => CoefficientCalculator::k1($this),
            'k2' => CoefficientCalculator::k2($this),
            'k3' => CoefficientCalculator::k3($this),

            'date' => $date->format(DATE_ATOM)
        ], '');
    }

    public function rules(): array
    {
        return [
            [['shortAsset', 'longAsset', 'capital', 'shortLiability', 'longLiability', 'profit', 'k1', 'k2', 'k3', 'date'], 'required'],
            [['shortAsset', 'longAsset', 'capital', 'shortLiability', 'longLiability', 'profit', 'k1', 'k2', 'k3'], 'double'],
        ];
    }

    public function getDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->date);
    }
}
