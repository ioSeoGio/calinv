<?php

namespace app\models\Portfolio;

use app\models\FinancialInstrument\Share;
use app\models\IssuerRating\IssuerRating;
use common\Database\BaseActiveRecord;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;

class PersonalShare extends BaseActiveRecord
{
    public static function tableName(): string
    {
        return 'personal_shares';
    }

    public function attributes(): array
    {
        return [
            '_id',
            'share_id',
            'buyPrice',
            'amount',
            'buyDate',
        ];
    }

    public function rules(): array
    {
        return [
            [[
                'share_id',
                'buyPrice',
                'amount',
                'buyDate',
            ], 'required'],
        ];
    }

    public function getShare(): ActiveQuery|ActiveQueryInterface
    {
        return $this->hasOne(Share::class, ['_id' => 'share_id']);
    }
}
