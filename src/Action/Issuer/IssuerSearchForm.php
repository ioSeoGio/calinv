<?php

namespace src\Action\Issuer;

use src\Entity\Issuer\Issuer;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class IssuerSearchForm extends Model
{
    public string $name = '';
    public string $bikScore = '';

    public function rules(): array
    {
        return [
            [['name', 'bikScore'], 'string'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = Issuer::find()
            ->with('businessReputationInfo')
            ->with('shares');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'bikScore', $this->bikScore]);

        return $dataProvider;
    }
}