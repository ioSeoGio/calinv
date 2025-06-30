<?php

namespace src\Action\Issuer;

use yii\base\Model;

class IssuerCreateForm extends Model
{
    public string $pid = '101489077';

    public function rules(): array
    {
        return [
            [[
                'pid'
            ], 'required', 'message' => 'Заполните.'],
            [['pid'], 'string'],
        ];
    }
}