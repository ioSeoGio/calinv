<?php

namespace src\Action\Issuer\FinancialReport\ProfitLossReport;

use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReport;
use src\Entity\Issuer\Issuer;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ProfitLossReportSearchForm extends Model
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
        $query = ProfitLossReport::find()
            ->joinWith('issuer')
            ->andWhere([Issuer::tableName() . '.id' => $issuer->id])
            ->addOrderBy(['_year' => SORT_DESC]);

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