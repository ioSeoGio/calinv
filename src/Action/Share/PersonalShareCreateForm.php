<?php

namespace src\Action\Share;

use DateTime;
use yii\base\Model;

class PersonalShareForm extends Model
{
    public string $share_id = 'а21';
    public int $amount = 1;
    public float $buyPrice = 1;
    public string $buyDate;

    public function init(): void
    {
        $this->buyDate = (new DateTime())->modify('+18 months')->format('d.m.Y');
        parent::init();
    }

    public function rules(): array
    {
        return [
            [[
                'share_id',
                'amount',
                'buyPrice',
                'buyDate',
            ], 'required', 'message' => 'Заполните.'],
            ['buyDate', 'datetime', 'format' => 'php:d.m.Y'],
        ];
    }
}