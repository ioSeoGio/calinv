<?php

namespace src\Integration\Bcse;

use src\Entity\Share\Share;
use src\Integration\Bcse\ShareInfo\BcseShareInfoFetcher;
use src\Integration\CentralDepo\CentralDepoHttpClient;

class BcseUrlHelper
{
    public static function getShareUrl(Share $model): string
    {
        return sprintf(BcseHttpClient::BASE_URL . BcseShareInfoFetcher::PATH, $model->issuer->_pid, $model->registerNumber);
    }

    public static function getShareUrlFromString(string $pid, string $registerNumber): string
    {
        return sprintf(BcseHttpClient::BASE_URL . BcseShareInfoFetcher::PATH, $pid, $registerNumber);
    }
}