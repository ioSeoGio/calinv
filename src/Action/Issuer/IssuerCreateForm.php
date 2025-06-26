<?php

namespace src\Action\Issuer;

use yii\base\Model;

class IssuerCreateForm extends Model
{
    public string $issuerName = 'Amazon';
    public string $bikScore = 'AA+';

    public function rules(): array
    {
        return [
            [[
                'issuerName',
                'bikScore',
            ], 'required', 'message' => 'Заполните.'],
            [['issuerName', 'bikScore'], 'string'],
        ];
    }
}