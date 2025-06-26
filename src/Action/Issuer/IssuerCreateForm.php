<?php

namespace src\Action\Issuer;

use yii\base\Model;

class CreateIssuerForm extends Model
{
    public string $issuer = 'Amazon';
    public string $bikScore = 'AA+';

    public function rules(): array
    {
        return [
            [[
                'issuer',
                'bikScore',
            ], 'required', 'message' => 'Заполните.'],
            [['issuer', 'bikScore'], 'string'],
        ];
    }
}