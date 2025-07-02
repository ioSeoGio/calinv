<?php

namespace src\Action\Issuer\Event;

use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\IssuerEvent\IssuerEvent;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class IssuerEventSearchForm extends Model
{
    public string $issuerName = '';
    public string $pid = '';

    public function rules(): array
    {
        return [
            [['issuerName', 'pid'], 'string'],
        ];
    }

    public function search(Issuer $issuer, array $params): ActiveDataProvider
    {
        $query = IssuerEvent::find()
            ->joinWith('issuer')
            ->andWhere(['issuer._pid' => $issuer->pid->id]);

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

        $query->andFilterWhere(['like', 'issuer.name', $this->issuerName])
            ->andFilterWhere(['like', 'pid', $this->pid]);

        return $dataProvider;
    }
}