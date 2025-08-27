<?php

namespace src\Action\TradingDayResult;

use DateTimeImmutable;
use src\Entity\Share\Deal\ShareDealRecord;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class LastTradingDayResultSearch extends Model
{
    public function rules(): array
    {
        return [
        ];
    }

    public function search(\DateTimeImmutable $selectedDay, array $params): ActiveDataProvider
    {
        $currentDay = \DateTimeImmutable::createFromFormat('!Y-m-d', time());
        $tradingDayResultSearch = new TradingDayResultSearch();

        $currentDayResultExists = ShareDealRecord::find()
            ->andWhere(['_date' => $selectedDay->format(DATE_ATOM)])
            ->exists();

        if ($currentDayResultExists) {
            return $tradingDayResultSearch->search($currentDay, $params);
        }

        $lastDateImmutable = $this->getLastAvailableDay();
        if ($lastDateImmutable === null) {
            return new ActiveDataProvider([]);
        }

        return $tradingDayResultSearch->search($lastDateImmutable, $params);
    }

    public function getLastAvailableDay(): ?DateTimeImmutable
    {
        $lastDate = (new Query())
            ->select('MAX(_date)')
            ->from(ShareDealRecord::tableName())
            ->scalar();
        $lastDateImmutable = $lastDate ? DateTimeImmutable::createFromFormat(DATE_ATOM, $lastDate) : null;

        return $lastDateImmutable;
    }
}
