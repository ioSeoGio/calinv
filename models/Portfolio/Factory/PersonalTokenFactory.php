<?php

namespace app\models\Portfolio\Factory;

use app\controllers\Portfolio\Form\PersonalTokenForm;
use app\models\Portfolio\PersonalToken;
use MongoDB\BSON\ObjectId;

class PersonalTokenFactory
{
    public function __construct(
    ) {
    }

    public function create(PersonalTokenForm $form): PersonalToken
    {
        $model = new PersonalToken();
        $model->load([
            'token_id' => new ObjectId($form->token_id),
            'buyPrice' => $form->buyPrice,
            'amount' => $form->amount,
            'buyDate' => $form->buyDate,
        ], '');

        return $model;
    }
}
