<?php

namespace src\Action\Issuer\FinancialReport\AccountingBalance;

use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalance;
use src\Entity\Issuer\Issuer;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class AccountingBalanceSearchForm extends Model
{
    public string $year = '';

    public function rules(): array
    {
        return [
            [['year'], 'string'],
        ];
    }

    public function search(Issuer $issuer, $params): ActiveDataProvider
    {
        $query = AccountingBalance::find()
            ->joinWith('issuer')
            ->andWhere([Issuer::tableName() . '.id' => $issuer->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'year', $this->year]);

        return $dataProvider;
    }
}