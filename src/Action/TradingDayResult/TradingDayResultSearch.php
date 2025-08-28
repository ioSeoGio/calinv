<?php

namespace src\Action\TradingDayResult;

use src\Entity\Issuer\Issuer;
use src\Entity\Share\Deal\ShareDealRecord;
use src\Entity\Share\Share;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class TradingDayResultSearch extends Model
{
    public $name = '';

    public function rules(): array
    {
        return [
            [['name'], 'string'],
            [['name'], 'trim'],
        ];
    }

    public static function fill(array $params): self
    {
        $self = new self();
        $self->load($params);

        if ($self->validate()) {
            return $self;
        }

        return new self();
    }

    public function search(\DateTimeImmutable $selectedDay, array $params): ActiveDataProvider
    {
        $selectedDay = \DateTimeImmutable::createFromFormat('!Y-m-d', $selectedDay->format('Y-m-d'));

        $previousDaySubQuery = (new Query())
            ->select(['share_id', 'MAX(_date) AS max_date'])
            ->from(ShareDealRecord::tableName())
            ->andWhere(['<', '_date', $selectedDay->format(DATE_ATOM)])
            ->groupBy('share_id');

        $query = (new Query())
            ->from('share s')
            ->select([
                's.id as shareId',
                's.registerNumber',
                'issuer.name as issuerName',
                's.name',
                's."closingDate"',
                'current_sd.id AS shareDealId',

                'current_sd."maxPrice" AS selectedDayMaxPrice',
                'current_sd."minPrice" AS selectedDayMinPrice',
                'current_sd."weightedAveragePrice" AS selectedDayPrice',
                'current_sd."_date" AS selectedDayDate',
                'current_sd."totalSum" AS selectedDayTotalSum',
                'current_sd."totalAmount" AS selectedDayTotalAmount',
                'current_sd."totalDealAmount" AS selectedDayTotalDealAmount',

                'prev_sd."maxPrice" AS previousDayMaxPrice',
                'prev_sd."minPrice" AS previousDayMinPrice',
                'prev_sd."weightedAveragePrice" AS previousDayPrice',
                'prev_sd."_date" AS previousDayDate',
                'prev_sd."totalSum" AS previousDayTotalSum',
                'prev_sd."totalAmount" AS previousDayTotalAmount',
                'prev_sd."totalDealAmount" AS previousDayTotalDealAmount',

                '(current_sd."minPrice" - prev_sd."minPrice") AS minPriceDifference',
                '(current_sd."maxPrice" - prev_sd."maxPrice") AS maxPriceDifference',
                '(current_sd."weightedAveragePrice" - prev_sd."weightedAveragePrice") AS difference',
            ])
            ->leftJoin([
                    'current_sd' => ShareDealRecord::tableName()
                ],
                's.id = current_sd.share_id AND current_sd._date = :selectedDate',
                [':selectedDate' => $selectedDay->format(DATE_ATOM)]
            )
            ->leftJoin(['prev_dates' => $previousDaySubQuery], 's.id = prev_dates.share_id')
            ->leftJoin([
                    'prev_sd' => ShareDealRecord::tableName()
                ],
                'prev_dates.share_id = prev_sd.share_id AND prev_dates.max_date = prev_sd._date'
            )
            ->innerJoin(['issuer' => Issuer::tableName()], 'issuer.id = s.issuer_id')
            ->andWhere(['NOT', ['current_sd."_date"' => null]])
            ->andWhere(['s.closingDate' => null]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'name',
                    'issuerName' => [
                        'asc' => ['issuer.name' => SORT_ASC],
                        'desc' => ['issuer.name' => SORT_DESC],
                    ],
                    'registerNumber',
                    'selectedDayMaxPrice',
                    'selectedDayMinPrice',
                    'selectedDayPrice',
                    'selectedDayTotalSum',
                    'selectedDayTotalAmount',
                    'selectedDayTotalDealAmount',
                    'previousDayMaxPrice',
                    'previousDayMinPrice',
                    'previousDayPrice',
                    'difference',
                ],
            ],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['ilike', 's.name', $this->name]);

        return $dataProvider;
    }
}