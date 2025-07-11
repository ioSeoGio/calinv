<?php

namespace src\Action\Issuer\FinancialReport\CashFlowReport;

use src\Entity\Issuer\FinanceReport\CashFlowReport\CashFlowReport;
use src\Entity\Issuer\Issuer;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CashFlowReportSearchForm extends Model
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
        $query = CashFlowReport::find()
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