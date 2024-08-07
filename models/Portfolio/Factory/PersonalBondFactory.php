<?php

namespace app\models\Portfolio\Factory;

use app\controllers\Portfolio\Form\PersonalBondForm;
use app\models\Portfolio\PersonalBond;
use MongoDB\BSON\ObjectId;
use Yii;

class PersonalBondFactory
{
    public function __construct(
    ) {
    }

    public function create(PersonalBondForm $form): PersonalBond
    {
        $model = new PersonalBond();
        $model->load([
            'bond_id' => new ObjectId($form->bond_id),
            'buyPrice' => $form->buyPrice,
            'amount' => $form->amount,
            'buyDate' => $form->buyDate,
            'user_id' => new ObjectId(Yii::$app->user->identity->getId()),
        ], '');

        return $model;
    }
}
