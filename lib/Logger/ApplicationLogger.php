<?php

namespace lib\Logger;

use Psr\Log\LogLevel;
use Yii;

class ApplicationLogger
{
    public static function log(\Throwable $e): void
    {
        Yii::getLogger()->log($e->getMessage(), LogLevel::ERROR);
    }
}