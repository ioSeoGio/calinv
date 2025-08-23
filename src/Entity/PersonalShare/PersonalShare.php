<?php

namespace src\Entity\PersonalShare;

use lib\Database\BaseActiveRecord;
use src\Action\Share\PersonalShareCreateForm;
use src\Entity\Share\Share;
use src\Entity\User\User;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property int $share_id
 * @property int $user_id
 * @property int $amount
 * @property float $buyPrice
 * @property string $boughtAt
 *
 * @property Share $share
 */
class PersonalShare extends BaseActiveRecord
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

    public function getTotalBoughtSum(): float
    {
        return $this->buyPrice * $this->amount;
    }

    public function getTotalCurrentPriceSum(): float
    {
        return ($this->share->currentPrice ?: 0) * $this->amount;
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
