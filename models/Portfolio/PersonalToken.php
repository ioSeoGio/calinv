<?php

namespace app\models\Portfolio;

use app\models\FinancialInstrument\Token;
use common\Database\BaseActiveRecord;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;

class PersonalToken extends BaseActiveRecord
{
    public static function tableName(): string
    {
        return 'personal_tokens';
    }

    public function attributes(): array
    {
        return [
            '_id',
            'token_id',
            'buyPrice',
            'amount',
            'buyDate',
        ];
    }

    public function rules(): array
    {
        return [
            [[
                'token_id',
                'buyPrice',
                'amount',
                'buyDate',
            ], 'required'],
        ];
    }

    public function getToken(): ActiveQuery|ActiveQueryInterface
    {
        return $this->hasOne(Token::class, ['_id' => 'token_id']);
    }
}
