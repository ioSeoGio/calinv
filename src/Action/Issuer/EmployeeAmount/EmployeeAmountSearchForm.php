<?php

namespace src\Action\Issuer\EmployeeAmount;

use src\Entity\Issuer\EmployeeAmount\EmployeeAmountRecord;
use src\Entity\Issuer\Issuer;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class EmployeeAmountSearchForm extends Model
{
    public function rules(): array
    {
        return [
        ];
    }

    public function search(Issuer $issuer, array $params): ActiveDataProvider
    {
        $query = EmployeeAmountRecord::find()
            ->andWhere(['issuerId' => $issuer->id])
            ->addOrderBy(['_date' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}