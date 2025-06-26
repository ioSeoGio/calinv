<?php

namespace src\Action\Portfolio;

use src\Entity\User\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PortfolioSearch extends Model
{
    public string $username = '';

    public function rules(): array
    {
        return [
            [['username'], 'string'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = User::find()
            ->with(['personalShares']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'username', $this->username]);

        return $dataProvider;
    }
}