<?php

namespace app\models\Portfolio;

use src\Entity\Share\Share;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;

class PersonalShare extends ActiveRecord
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
            'user_id',
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
                'user_id',
            ], 'required'],
        ];
    }

    public function getShare(): ActiveQuery|ActiveQueryInterface
    {
        return $this->hasOne(Share::class, ['_id' => 'share_id']);
    }
}
