<?php

namespace src\Action\Share;

use DateTime;
use Yii;
use yii\base\Model;

class PersonalShareCreateForm extends Model
{
    public $share_id;
    public $amount;
    public $buyPrice;
    public $boughtAt;

    public function init(): void
    {
        $this->boughtAt = (new DateTime())->format('d.m.Y');
        parent::init();
    }

    public function attributeLabels(): array
    {
        return [
            'buyPrice' => 'Закупочная цена',
            'boughtAt' => 'Дата покупки',
            'amount' => 'Количество',
        ];
    }

    public function rules(): array
    {
        return [
            [[
                'share_id',
                'amount',
                'buyPrice',
                'boughtAt',
            ], 'required', 'message' => 'Заполните.'],
            ['boughtAt', 'datetime', 'format' => 'php:d.m.Y'],
            [['amount', 'buyPrice'], 'number', 'min' => 0],
        ];
    }
}