<?php

namespace src\Action\Issuer\Rating;

use src\Entity\Issuer\EsgRating\EsgRatingInfo;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class EsgRatingInfoSearch extends Model
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
        $query = EsgRatingInfo::find()
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