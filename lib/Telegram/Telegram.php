<?php

namespace lib\Telegram;

use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\httpclient\Response;

class Telegram
{
    public function __construct(
        private string $botToken,
    ) {
    }

    public function sendMessage(
        string $message,
        string $channelId,
        TelegramParseModeEnum $parseMode = TelegramParseModeEnum::HTML,
        bool $disableWebPagePreview = false,
        bool $disableAudioNotification = false,
        ?int $threadId = null
    ): Response {
        return $this->query('sendMessage', [
            'chat_id' => $channelId,
            'text' => $message,
            'parse_mode' => $parseMode->value,
            'message_thread_id' => $threadId,

            'disable_web_page_preview' => $disableWebPagePreview,
            'disable_notification' => $disableAudioNotification,
        ]);
    }

    private function query(string $action, array $params): Response
    {
        $url = "https://api.telegram.org/bot{$this->botToken}/$action";
        $client = new Client();

        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($url)
            ->setData($params)
            ->send();

        /* Логирование контента */
        \Yii::info($response->getContent());

        if (!$response->data['ok']) {
            $code = ArrayHelper::getValue($response->data, 'error_code');
            throw new TelegramApiException($response->getContent(), $code);
        }

        return $response;
    }
}
