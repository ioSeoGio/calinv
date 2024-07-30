<?php

namespace app\controllers\FinancialInstrument\Search;

use app\models\FinancialInstrument\Share;
use MongoDB\BSON\ObjectId;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ShareSearchForm extends Model
{
    public string $issuerId = 'All';
    public string $name = '';

    public function rules(): array
    {
        return [
            [['issuerId', 'name'], 'string'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = Share::find()
            ->with(['issuerRating']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);
        if ($this->issuerId !== 'All' && $this->issuerId !== '') {
            $query->andFilterWhere(['issuer_id' => new ObjectId($this->issuerId)]);
        }

        return $dataProvider;
    }
}
