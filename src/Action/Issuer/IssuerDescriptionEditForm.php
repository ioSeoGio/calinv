<?php

namespace src\Action\Issuer;

use src\Entity\Issuer\Issuer;
use yii\base\Model;

class IssuerDescriptionEditForm extends Model
{
    public string $description = '';

    public function __construct(
        ?Issuer $issuer = null,
        $config = []
    ) {
        $this->description = $issuer->description ?? '';

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['description'], 'string'],
        ];
    }
}