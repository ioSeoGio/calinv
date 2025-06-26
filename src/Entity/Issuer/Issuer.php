<?php

namespace src\Entity\Issuer;

use src\Action\Issuer\IssuerCreateForm;
use src\Entity\Share\Share;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property string $name
 * @property string $bikScore
 * @property ?float $expressRating
 */
class Issuer extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'issuer';
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Имя выпуска',
            'bikScore' => 'BIK рейтинг',
            'expressRating' => 'Экспресс рейнтинг',
        ];
    }

    public static function make(
        IssuerCreateForm $form
    ): self {
        $model = new Issuer([
            'name' => $form->issuerName,
            'bikScore' => $form->bikScore,
            'expressRating' => 0,
        ]);
        $model->save();

        return $model;
    }

    public function getShares(): ActiveQuery
    {
        return $this->hasMany(Share::class, ['issuer_id' => 'id']);
    }
}
