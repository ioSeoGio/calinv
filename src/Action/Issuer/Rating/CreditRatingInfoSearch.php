<?php

namespace src\Action\Issuer\Rating;

use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfo;
use src\Entity\Issuer\CreditRating\CreditRatingInfo;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CreditRatingInfoSearch extends Model
{
    public string $issuerName = '';

    public function rules(): array
    {
        return [
            [['issuerName'], 'string'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = CreditRatingInfo::find()
            ->with('issuer')
            ->addOrderBy(['_rating' => SORT_ASC]);

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

        $query->andFilterWhere(['ilike', 'issuerName', $this->issuerName]);

        return $dataProvider;
    }
}