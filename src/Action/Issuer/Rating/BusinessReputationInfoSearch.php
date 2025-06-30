<?php

namespace src\Action\Issuer\Rating;

use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfo;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class BusinessReputationInfoSearch extends Model
{
    public string $issuerName = '';
    public string $pid = '';

    public function rules(): array
    {
        return [
            [['issuerName', 'pid'], 'string'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = BusinessReputationInfo::find()
            ->with('issuer');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 200,
            ],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'issuerName', $this->issuerName])
            ->andFilterWhere(['like', 'pid', $this->pid]);

        return $dataProvider;
    }
}