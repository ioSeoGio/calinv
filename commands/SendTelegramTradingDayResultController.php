<?php

namespace app\commands;

use src\Cron\CronTelegramTradingDayResultSender;
use yii\console\Controller;
use yii\console\ExitCode;

class SendTelegramTradingDayResultController extends Controller
{
    public function __construct(
        $id,
        $module,

        private CronTelegramTradingDayResultSender $cronTelegramTradingDayResultSender,

        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionSend(): int
    {
        $this->cronTelegramTradingDayResultSender->sendMany();

        return ExitCode::OK;
    }
}
