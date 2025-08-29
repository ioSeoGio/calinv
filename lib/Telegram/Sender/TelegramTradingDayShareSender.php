<?php

namespace lib\Telegram\Sender;

use lib\Telegram\Helper\TelegramDiffPrinter;
use lib\Telegram\Telegram;
use lib\Telegram\TelegramParseModeEnum;
use Yii;
use yii\helpers\Url;
use yii\httpclient\Response;

class TelegramTradingDayShareSender
{
    public function __construct(
        private Telegram $telegram,
        private TelegramDiffPrinter $telegramDiffPrinter,
    ) {
    }

    public function send(array $data): Response
    {
        return $this->telegram->sendMessage(
            message: $this->generateMessage($data),
            channelId: Yii::$app->params['telegramChannelId'],
            parseMode: TelegramParseModeEnum::MARKDOWN,
            disableWebPagePreview: true,
            disableAudioNotification: true,
            threadId: Yii::$app->params['telegramTradingDayResultTopicId'],
        );
    }

    /** @var array{
     * shareId: int,
     * shareDealId: int,
     * registerNumber: string,
     * issuerName: string,
     * name: string,
     *
     * selectedDayMaxPrice: float,
     * selectedDayMinPrice: float,
     * selectedDayPrice: float,
     * selectedDayDate: string,
     * selectedDayTotalSum: float,
     * selectedDayTotalAmount: int,
     * selectedDayTotalDealAmount: int,
     *
     * previousDayMaxPrice: float,
     * previousDayMinPrice: float,
     * previousDayPrice: float,
     * previousDayDate: string,
     * previousDayTotalSum: float,
     * previousDayTotalAmount: int,
     * previousDayTotalDealAmount: int,
     *
     * minPriceDifference: float,
     * maxPriceDifference: float,
     * difference: float,
     * } $model
     **/
    private function generateMessage(array $model): string
    {
        // Format date
        $previousDayDate = Yii::$app->formatter->asDate($model['previousDayDate'], 'full');
        $selectedDayDate = Yii::$app->formatter->asDate($model['selectedDayDate'], 'full');
        $url = Url::to(["/trading-day-result#{$model['shareDealId']}"]);

        // Build the message
        $message = "📊 *{$model['name']}*\n";
        $message .= "Торговый день: $selectedDayDate\n\n";
        $message .= "**Средневзвешенная цена:** {$model['selectedDayPrice']} BYN {$this->telegramDiffPrinter->getChange($model['selectedDayPrice'], $model['previousDayPrice'])}\n";
        $message .= "**Мин. цена:** {$model['selectedDayMinPrice']} BYN {$this->telegramDiffPrinter->getChange($model['selectedDayMinPrice'], $model['previousDayMinPrice'])}\n";
        $message .= "**Макс. цена:** {$model['selectedDayMaxPrice']} BYN {$this->telegramDiffPrinter->getChange($model['selectedDayMaxPrice'], $model['previousDayMaxPrice'])}\n\n";
        $message .= "**Сумма сделок:** {$model['selectedDayTotalSum']} BYN {$this->telegramDiffPrinter->getChange($model['selectedDayTotalSum'], $model['previousDayTotalSum'])}\n";
        $message .= "**Кол-во акций:** {$model['selectedDayTotalAmount']} {$this->telegramDiffPrinter->getChange($model['selectedDayTotalAmount'], $model['previousDayTotalAmount'])}\n";
        $message .= "**Кол-во сделок:** {$model['selectedDayTotalDealAmount']} {$this->telegramDiffPrinter->getChange($model['selectedDayTotalDealAmount'], $model['previousDayTotalDealAmount'])}\n\n";
        $message .= "*Последнее изменение:* {$previousDayDate}\n";
        $message .= "🔗 [Подробности]($url)";

        return $message;
    }
}