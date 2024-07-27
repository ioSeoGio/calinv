<?php

namespace app\controllers\IssuerRatingCalculator;

use app\models\IssuerRating\IssuerRating;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class IssuerRatingSearchForm extends Model
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
        $query = IssuerRating::find();

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
