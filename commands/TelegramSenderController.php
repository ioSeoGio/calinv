<?php

namespace app\commands;

use src\Cron\CronTelegramTradingDayResultSender;
use yii\console\Controller;
use yii\console\ExitCode;

class TelegramSenderController extends Controller
{
    public function __construct(
        $id,
        $module,

        private CronTelegramTradingDayResultSender $cronTelegramTradingDayResultSender,

        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionTradingDay(): int
    {
        if (YII_ENV_PROD) {
            $this->cronTelegramTradingDayResultSender->sendMany();
        } else {
            echo "[CRON][INFO] Not production mode, skipping sending telegram trading day result.\n";
        }

        return ExitCode::OK;
    }
}
