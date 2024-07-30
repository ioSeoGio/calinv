<?php

namespace app\models\FinancialInstrument;

use app\models\IssuerRating\IssuerRating;
use common\Database\BaseActiveRecord;
use yii\db\ActiveQueryInterface;
use yii\mongodb\ActiveQuery;

class Share extends BaseActiveRecord
{
    public static function tableName(): string
    {
        return 'shares';
    }

    public function attributes(): array
    {
        return [
            '_id',
            'name',
            'issuer_id',
            'denomination',
            'currentPrice',
            'volumeIssued',
        ];
    }

    public function rules(): array
    {
        return [
            [[
                'name',
                'issuer_id',
                'denomination',
                'currentPrice',
                'volumeIssued',
            ], 'required'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Имя выпуска',
            'issuer_id' => 'Эмитент',
            'denomination' => 'Номинал',
            'currentPrice' => 'Текущая цена',
            'volumeIssued' => 'Объем выпуска',
        ];
    }

    public function getIssuerRating(): \yii\db\ActiveQuery|ActiveQueryInterface
    {
        return $this->hasOne(IssuerRating::class, ['_id' => 'issuer_id']);
    }
}
