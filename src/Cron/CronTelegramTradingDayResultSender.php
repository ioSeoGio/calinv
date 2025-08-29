<?php

namespace src\Cron;

use lib\Telegram\Sender\TelegramTradingDayShareSender;
use lib\Telegram\Sender\TelegramTradingDaySummarySender;
use src\Action\TradingDayResult\LastTradingDayResultSearch;

class CronTelegramTradingDayResultSender
{
    public function __construct(
        private TelegramTradingDayShareSender $telegramTradingDayResultSender,
        private LastTradingDayResultSearch $lastTradingDayResultSearch,
        private TelegramTradingDaySummarySender $telegramTradingDaySummarySender,
    ) {
    }

    public function sendMany(): void
    {
        $date = new \DateTimeImmutable();
        $dataProvider = $this->lastTradingDayResultSearch->search($date, []);

        foreach ($dataProvider->query->each() as $data) {
            $this->telegramTradingDayResultSender->send($data);
        }

        $this->telegramTradingDaySummarySender->send($date, clone $dataProvider->query);
    }
}
