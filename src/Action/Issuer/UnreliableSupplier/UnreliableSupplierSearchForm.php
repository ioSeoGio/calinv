<?php

namespace src\Action\Issuer\UnreliableSupplier;

use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfo;
use src\Entity\Issuer\UnreliableSupplier\UnreliableSupplier;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class UnreliableSupplierSearchForm extends Model
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
        $query = UnreliableSupplier::find()
            ->with('issuer')
            ->addOrderBy(['_addDate' => SORT_DESC]);

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

        $query->andFilterWhere(['ilike', 'issuerName', $this->issuerName])
            ->andFilterWhere(['like', 'pid', $this->pid]);

        return $dataProvider;
    }
}