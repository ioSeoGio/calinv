<?php

namespace src\Action\Share;

use src\Entity\PersonalShare\PersonalShare;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PersonalShareSearchForm extends Model
{
    public string $issuerId = 'All';
    public string $shareId = 'All';

    public function rules(): array
    {
        return [
            [['issuerId', 'shareId'], 'string'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = PersonalShare::find()
            ->joinWith(['share.issuer'])
            ->andWhere(['user_id' => $params['userId'] ?? Yii::$app->user->identity->getId()]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->shareId !== 'All' && $this->shareId !== '') {
            $query->andFilterWhere(['share_id' => $this->shareId]);
        }

        if ($this->issuerId !== 'All' && $this->issuerId !== '') {
            $query->andFilterWhere(['share.issuer.id' => $this->issuerId]);
        }

        return $dataProvider;
    }
}