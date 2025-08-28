<?php

namespace lib\Telegram\Sender;

use lib\Telegram\Telegram;
use lib\Telegram\TelegramParseModeEnum;
use Yii;
use yii\helpers\Url;
use yii\httpclient\Response;

class TelegramTradingDayResultSender
{
    public function __construct(
        private Telegram $telegram,
    ) {
    }

    public function send(array $data): Response
    {
        return $this->telegram->sendMessage(
            message: $this->generateMessage($data),
            channelId: Yii::$app->params['telegramChannelId'],
            parseMode: TelegramParseModeEnum::MARKDOWN,
            disableWebPagePreview: true,
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
        // Calculate percentage changes
        $priceChangePercent = round($model['difference'] / $model['previousDayPrice'] * 100, 2);
        $priceChangePercent = $priceChangePercent > 0 ? "+$priceChangePercent" : $priceChangePercent;

        $sumChangePercent = round(($model['selectedDayTotalSum'] - $model['previousDayTotalSum']) / $model['previousDayTotalSum'] * 100, 2);
        $sumChangePercent = $sumChangePercent > 0 ? "+$sumChangePercent" : $sumChangePercent;

        $amountChangePercent = round(($model['selectedDayTotalAmount'] - $model['previousDayTotalAmount']) / $model['previousDayTotalAmount'] * 100, 2);
        $amountChangePercent = $amountChangePercent > 0 ? "+$amountChangePercent" : $amountChangePercent;

        $dealChangePercent = round(($model['selectedDayTotalDealAmount'] - $model['previousDayTotalDealAmount']) / $model['previousDayTotalDealAmount'] * 100, 2);
        $dealChangePercent = $dealChangePercent > 0 ? "+$dealChangePercent" : $dealChangePercent;

        // Calculate changes
        $totalSumChange = $model['selectedDayTotalSum'] - $model['previousDayTotalSum'];
        $totalSumChange = $totalSumChange > 0 ? "+$totalSumChange" : $totalSumChange;

        $totalAmountChange = $model['selectedDayTotalAmount'] - $model['previousDayTotalAmount'];
        $totalAmountChange = $totalAmountChange > 0 ? "+$totalAmountChange" : $totalAmountChange;

        $dealAmountChange = $model['selectedDayTotalDealAmount'] - $model['previousDayTotalDealAmount'];
        $dealAmountChange = $dealAmountChange > 0 ? "+$dealAmountChange" : $dealAmountChange;

        // Determine emoji based on changes
        $priceEmoji = $model['difference'] >= 0 ? '📈' : '📉';
        $sumEmoji = ($model['selectedDayTotalSum'] - $model['previousDayTotalSum']) >= 0 ? '📈' : '📉';
        $amountEmoji = ($model['selectedDayTotalAmount'] - $model['previousDayTotalAmount']) >= 0 ? '📈' : '📉';
        $dealEmoji = ($model['selectedDayTotalDealAmount'] - $model['previousDayTotalDealAmount']) >= 0 ? '📈' : '📉';

        // Format date
        $previousDayDate = Yii::$app->formatter->asDate($model['previousDayDate'], 'full');
        $selectedDayDate = Yii::$app->formatter->asDate($model['selectedDayDate'], 'full');
        $url = Url::to(["/trading-day-result#{$model['shareDealId']}"]);

        // Build the message
        $message = "📊 *{$model['name']}*\n";
        $message .= "Торговый день: $selectedDayDate\n\n";
        $message .= "**Средневзвешенная цена:** {$model['selectedDayPrice']} BYN ({$model['difference']}, $priceChangePercent% $priceEmoji)\n";
        $message .= "**Минимальная цена:** {$model['selectedDayMinPrice']} BYN\n";
        $message .= "**Максимальная цена:** {$model['selectedDayMaxPrice']} BYN\n";
        $message .= "**Сумма сделок:** {$model['selectedDayTotalSum']} BYN ($totalSumChange, $sumChangePercent% $sumEmoji)\n";
        $message .= "**Количество акций:** {$model['selectedDayTotalAmount']} ($totalAmountChange, $amountChangePercent% $amountEmoji)\n";
        $message .= "**Количество сделок:** {$model['selectedDayTotalDealAmount']} ($dealAmountChange, $dealChangePercent% $dealEmoji)\n\n";
        $message .= "*Предыдущий отчетный день:* {$previousDayDate}\n";
        $message .= "🔗 [Подробности]($url)";

        return $message;
    }
}