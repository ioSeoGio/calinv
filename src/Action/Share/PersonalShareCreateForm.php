<?php

namespace src\Action\Share;

use DateTime;
use Yii;
use yii\base\Model;

class PersonalShareCreateForm extends Model
{
    public string $share_id = 'а21';
    public int $amount = 1;
    public float $buyPrice = 1;
    public string $boughtAt;
    public int $user_id;

    public function init(): void
    {
        $this->boughtAt = (new DateTime())->modify('+18 months')->format('d.m.Y');
        $this->user_id = Yii::$app->user->id;
        parent::init();
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
        ];
    }
}