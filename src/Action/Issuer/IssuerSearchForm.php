<?php

namespace src\Action\Issuer;

use src\Entity\Issuer\Issuer;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SearchIssuerForm extends Model
{
    public string $issuer = '';
    public string $bikScore = '';

    public function rules(): array
    {
        return [
            [['issuer', 'bikScore'], 'string'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = Issuer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'issuer', $this->issuer])
            ->andFilterWhere(['like', 'bikScore', $this->bikScore]);

        return $dataProvider;
    }
}