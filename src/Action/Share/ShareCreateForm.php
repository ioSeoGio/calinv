<?php

namespace src\Action\Share;

use yii\base\Model;

class ShareCreateForm extends Model
{
    public string $name = 'а21';
    public string $issuer_id = '0';
    public float $denomination = 1;
    public float $volumeIssued = 1;
    public float $currentPrice = 1;

    public function rules(): array
    {
        return [
            [[
                'name',
                'issuer_id',
                'denomination',
                'volumeIssued',
                'currentPrice',
            ], 'required', 'message' => 'Заполните.'],
        ];
    }
}