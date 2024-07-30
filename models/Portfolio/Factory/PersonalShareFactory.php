<?php

namespace app\models\Portfolio\Factory;

use app\controllers\Portfolio\Form\PersonalShareForm;
use app\models\Portfolio\PersonalShare;
use MongoDB\BSON\ObjectId;
use Yii;

class PersonalShareFactory
{
    public function __construct(
    ) {
    }

    public function create(PersonalShareForm $form): PersonalShare
    {
        $model = new PersonalShare();
        $model->load([
            'share_id' => new ObjectId($form->share_id),
            'buyPrice' => $form->buyPrice,
            'amount' => $form->amount,
            'buyDate' => $form->buyDate,
            'user_id' => Yii::$app->user->identity->getId(),
        ], '');

        return $model;
    }
}
