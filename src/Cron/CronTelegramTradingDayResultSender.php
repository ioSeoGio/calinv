<?php

namespace src\Cron;

use lib\Telegram\Sender\TelegramTradingDayResultSender;
use src\Action\TradingDayResult\LastTradingDayResultSearch;
use src\Entity\Issuer\Issuer;

class CronTelegramTradingDayResultSender
{
    public function __construct(
        private TelegramTradingDayResultSender $telegramTradingDayResultSender,
        private LastTradingDayResultSearch $lastTradingDayResultSearch,
    ) {
    }

    public function sendMany(): void
    {
        $dataProvider = $this->lastTradingDayResultSearch->search(new \DateTimeImmutable(), []);

        foreach ($dataProvider->query->each() as $data) {
            $this->telegramTradingDayResultSender->send($data);
        }
    }
}
