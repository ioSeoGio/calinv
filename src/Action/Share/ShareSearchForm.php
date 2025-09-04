<?php

namespace src\Action\Share;

use src\Entity\Issuer\Issuer;
use src\Entity\Share\Share;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ShareSearchForm extends Model
{
    public string $issuerName = '';
    public string $name = '';
    public bool $isActive = false;

    public function rules(): array
    {
        return [
            [['issuerName', 'name'], 'string'],
            [['isActive'], 'boolean'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = Share::find()
            ->joinWith('issuer')
            ->with(['shareDeals'])
            ->addOrderBy(['issueDate' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['ilike', 'name', $this->name]);
        $query->andFilterWhere(['ilike', 'issuer.name', $this->issuerName]);

        if ($this->isActive) {
            $query->andWhere(['closingDate' => null]);
        }

        return $dataProvider;
    }
}