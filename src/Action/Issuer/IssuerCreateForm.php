<?php

namespace src\Action\Issuer;

use yii\base\Model;

class IssuerCreateForm extends Model
{
    public string $pid = '';

    public function rules(): array
    {
        return [
            [[
                'pid'
            ], 'required', 'message' => 'Заполните УНП.'],
            [['pid'], 'string'],
        ];
    }
}
