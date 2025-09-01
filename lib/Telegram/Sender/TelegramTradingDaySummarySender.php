<?php

namespace lib\Telegram\Sender;

use lib\Telegram\Helper\TelegramDiffPrinter;
use lib\Telegram\Telegram;
use lib\Telegram\TelegramParseModeEnum;
use Yii;
use yii\db\QueryInterface;
use yii\helpers\Url;
use yii\httpclient\Response;

class TelegramTradingDaySummarySender
{
    public function __construct(
        private Telegram $telegram,
        private TelegramDiffPrinter $telegramDiffPrinter,
    ) {
    }

    public function send(\DateTimeImmutable $date, QueryInterface $query): ?Response
    {
        $selectedDayDate = Yii::$app->formatter->asDate($date, 'full');
        $message = "📄*Краткий итог*\n Торговый день: $selectedDayDate\n\n";

        $topByGrowth = (clone $query)->orderBy(['((current_sd."weightedAveragePrice" - prev_sd."weightedAveragePrice") / prev_sd."weightedAveragePrice")' => SORT_DESC])->limit(5)->all();
        $message .= $topByGrowth ? $this->generateTopByGrowthMessage($topByGrowth) : '';
        $message .= "\n\n";

        $topByLoss = (clone $query)
            ->andWhere(['not', ['((current_sd."weightedAveragePrice" - prev_sd."weightedAveragePrice") / prev_sd."weightedAveragePrice")' => null]])
            ->orderBy(['((current_sd."weightedAveragePrice" - prev_sd."weightedAveragePrice") / prev_sd."weightedAveragePrice")' => SORT_ASC])->limit(5)->all();
        $message .= $topByLoss ? $this->generateTopByLossMessage($topByLoss) : '';
        $message .= "\n\n";

        $topByVolume = (clone $query)->orderBy(['current_sd."totalSum"' => SORT_DESC])->limit(5)->all();
        $message .= $topByVolume ? $this->generateTopByVolumeMessage($topByVolume) : '';

        if (empty($topByVolume) && empty($topByLoss) && empty($topByGrowth)) {
            return null;
        }

        return $this->telegram->sendMessage(
            message: $message,
            channelId: Yii::$app->params['telegramChannelId'],
            parseMode: TelegramParseModeEnum::MARKDOWN,
            disableWebPagePreview: true,
            disableAudioNotification: true,
            threadId: Yii::$app->params['telegramTradingDayResultTopicId'],
        );
    }

    private function generateTopByGrowthMessage(array $models): string
    {
        $url = Url::to(["/trading-day-result", 'sort' => '-differenceInPercent']);

        $message = "📈 *Топ роста цен в %*:\n";
        foreach ($models as $model) {
            $message .= "*{$model['name']}*: {$model['selectedDayPrice']} BYN {$this->telegramDiffPrinter->getChange($model['selectedDayPrice'], $model['previousDayPrice'])}\n";
        }
        $message .= "🔗 [Топ роста]($url)";

        return $message;
    }

    private function generateTopByLossMessage(array $models): string
    {
        $url = Url::to(["/trading-day-result", 'sort' => 'differenceInPercent']);

        $message = "📈 *Топ снижения цен в %*:\n";
        foreach ($models as $model) {
            $message .= "*{$model['name']}*: {$model['selectedDayPrice']} BYN {$this->telegramDiffPrinter->getChange($model['selectedDayPrice'], $model['previousDayPrice'])}\n";
        }
        $message .= "🔗 [Топ снижения]($url)";

        return $message;
    }

    private function generateTopByVolumeMessage(array $models): string
    {
        $url = Url::to(["/trading-day-result", 'sort' => '-selectedDayTotalAmount']);

        $message = "📈 *Топ объемов торгов*:\n";
        foreach ($models as $model) {
            $message .= "*{$model['name']}*: {$model['selectedDayTotalSum']} BYN {$this->telegramDiffPrinter->getChange($model['selectedDayTotalSum'], $model['previousDayTotalSum'])}\n";
        }
        $message .= "🔗 [Топ объемов торгов]($url)";

        return $message;
    }
}