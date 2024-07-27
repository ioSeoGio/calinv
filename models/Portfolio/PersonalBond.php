<?php

namespace app\models\Portfolio;

use app\models\FinancialInstrument\Bond;
use common\Database\BaseActiveRecord;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;

class PersonalBond extends BaseActiveRecord
{
    public static function tableName(): string
    {
        return 'personal_bonds';
    }

    public function attributes(): array
    {
        return [
            '_id',
            'bond_id',
            'buyPrice',
            'amount',
            'buyDate',
        ];
    }

    public function rules(): array
    {
        return [
            [[
                'bond_id',
                'buyPrice',
                'amount',
                'buyDate',
            ], 'required'],
        ];
    }

    public function getBond(): ActiveQuery|ActiveQueryInterface
    {
        return $this->hasOne(Bond::class, ['_id' => 'bond_id']);
    }
}
