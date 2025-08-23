<?php

namespace src\Entity\PersonalShare;

use src\Action\Share\PersonalShareCreateForm;
use src\Entity\Share\Share;
use src\Entity\User\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $share_id
 * @property int $user_id
 * @property int $amount
 * @property float $buyPrice
 * @property string $boughtAt
 *
 * @property Share $share
 */
class PersonalShare extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'personal_share';
    }

    public function attributeLabels(): array
    {
        return [
            'amount' => 'кол-во',
        ];
    }

    public static function make(PersonalShareCreateForm $form): self
    {
        $model = new PersonalShare([
            'share_id' => $form->share_id,
            'user_id' => \Yii::$app->user->id,
            'amount' =>  $form->amount,
            'buyPrice' => $form->buyPrice,
            'boughtAt' => $form->boughtAt,
        ]);
        $model->save();

        return $model;
    }

    public function getShare(): ActiveQuery
    {
        return $this->hasOne(Share::class, ['id' => 'share_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
