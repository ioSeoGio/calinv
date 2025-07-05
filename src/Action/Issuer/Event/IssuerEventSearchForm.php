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

    public function searchImportant(Issuer $issuer): ActiveDataProvider
    {
        $dataProvider = $this->search($issuer, []);
        $dataProvider->query->andWhere(['>=', '_eventDate', (new \DateTime())->modify('-2 year')->format(DATE_ATOM)]);

        $criteria = [];
        foreach (IssuerEvent::IMPORTANT_EVENTS as $importantEvent) {
            $criteria[] = ['like', 'eventName', $importantEvent];
        }
        $dataProvider->query->andFilterWhere(['OR', ...$criteria]);

        $dataProvider->pagination->pageSize = 3;

        return $dataProvider;
    }

    public function search(Issuer $issuer, array $params): ActiveDataProvider
    {
        $query = IssuerEvent::find()
            ->joinWith('issuer')
            ->andWhere(['issuer._pid' => $issuer->pid->id])
            ->addOrderBy(['_eventDate' => SORT_DESC]);

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

        return $dataProvider;
    }
}