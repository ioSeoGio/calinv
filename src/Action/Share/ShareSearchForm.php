<?php

namespace src\Action\Share;

use src\Entity\Share\Share;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ShareSearchForm extends Model
{
    public string $issuerId = 'All';
    public string $name = '';
    public bool $isActive = false;

    public function rules(): array
    {
        return [
            [['issuerId', 'name'], 'string'],
            [['isActive'], 'boolean'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = Share::find()
            ->with(['issuer']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);
        if ($this->issuerId !== 'All' && $this->issuerId !== '') {
            $query->andFilterWhere(['issuer_id' => $this->issuerId]);
        }

        if ($this->isActive) {
            $query->andWhere(['closingDate' => null]);
        }

        return $dataProvider;
    }
}