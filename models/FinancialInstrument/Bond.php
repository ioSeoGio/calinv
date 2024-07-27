<?php

namespace app\models\FinancialInstrument;

use app\models\IssuerRating\IssuerRating;
use common\Database\BaseActiveRecord;
use yii\db\ActiveQueryInterface;
use yii\mongodb\ActiveQuery;

class Bond extends BaseActiveRecord
{
    public static function tableName(): string
    {
        return 'bonds';
    }

    public function attributes(): array
    {
        return [
            '_id',
            'name',
            'issuer_id',
            'denomination',
            'rate',
            'currentPrice',
            'volumeIssued',
            'maturityDate',
            'issueDate',
        ];
    }

    public function rules(): array
    {
        return [
            [[
                'name',
                'issuer_id',
                'denomination',
                'rate',
                'currentPrice',
                'volumeIssued',
                'maturityDate',
                'issueDate',
            ], 'required'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Имя выпуска',
            'issuer_id' => 'Эмитент',
            'denomination' => 'Номинал',
            'rate' => 'Ставка',
            'currentPrice' => 'Текущая цена',
            'volumeIssued' => 'Объем выпуска',
            'maturityDate' => 'Дата погашения',
            'issueDate' => 'Дата выпуска',
        ];
    }

    public function getIssuerRating(): \yii\db\ActiveQuery|ActiveQueryInterface
    {
        return $this->hasOne(IssuerRating::class, ['_id' => 'issuer_id']);
    }
}
