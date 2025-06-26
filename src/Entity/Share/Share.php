<?php

namespace src\Entity\Share;

use lib\BaseActiveRecord;
use src\Action\Share\ShareCreateForm;
use src\Entity\Issuer\Issuer;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 * @property int $issuer_id
 * @property Issuer $issuer
 * @property float $denomination
 * @property float $currentPrice
 * @property int $volumeIssued
 */
class Share extends BaseActiveRecord
{
    public static function tableName(): string
    {
        return 'share';
    }

    public function attributes(): array
    {
        return [
            'id',
            'name',
            'issuer_id',
            'denomination',
            'currentPrice',
            'volumeIssued',
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Имя выпуска',
            'issuer_id' => 'Эмитент',
            'denomination' => 'Номинал',
            'currentPrice' => 'Текущая цена',
            'volumeIssued' => 'Объем выпуска',
        ];
    }

    public static function make(ShareCreateForm $form): Share
    {
        $share = new Share([
            'name' => $form->name,
            'issuer_id' => $form->issuer_id,
            'denomination' => $form->denomination,
            'currentPrice' => $form->currentPrice,
            'volumeIssued' => $form->volumeIssued,
        ]);
        $share->save();

        return $share;
    }

    public function getIssuer(): ActiveQuery
    {
        return $this->hasOne(Issuer::class, ['id' => 'issuer_id']);
    }
}
