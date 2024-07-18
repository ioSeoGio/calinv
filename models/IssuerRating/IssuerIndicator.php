<?php

namespace app\models\IssuerRating;

use yii\base\Model;

class IssuerIndicator extends Model
{
    public string $date;

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

            'date' => $date->format(DATE_ATOM)
        ], '');
    }

    public function rules(): array
    {
        return [
            [['shortAsset', 'longAsset', 'capital', 'shortLiability', 'longLiability', 'profit', 'date'], 'required'],
            [['shortAsset', 'longAsset', 'capital', 'shortLiability', 'longLiability', 'profit'], 'double'],
        ];
    }

    public function getDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->date);
    }
}
