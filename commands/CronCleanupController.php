<?php

namespace app\commands;

use src\Entity\Issuer\Issuer;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class CronCleanupController extends Controller
{
    public function __construct(
        $id,
        $module,

        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionRemove(): int
    {
        $issuers = Issuer::find()
            ->orWhere(['_pid' => null])
            ->orWhere(['_pid' => '']);

        $issuersAmount = $issuers->count();
        Yii::info('[CRON] Чистка битых эмитентов, кол-во: ' . $issuersAmount);
        echo '[CRON] Чистка битых эмитентов, кол-во: ' . $issuersAmount . PHP_EOL;

        /** @var Issuer $issuer */
        foreach ($issuers->each() as $issuer) {
            try {
                $issuer->delete();
            } catch (\Throwable $e) {
                $errorClass = get_class($e);
                Yii::info("[CRON][ERROR] Чистка битых эмитентов, ошибка: {$errorClass} {$e->getMessage()}");
                echo "[CRON][ERROR] Чистка битых эмитентов, ошибка: {$errorClass} {$e->getMessage()}";
            }
        }

        return ExitCode::OK;
    }

    public function actionHide(): int
    {
        $issuers = Issuer::find()
            ->orWhere(['name' => null]);

        $issuersAmount = $issuers->count();
        Yii::info('[CRON] Прячем эмитентов без имени, кол-во: ' . $issuersAmount);
        echo '[CRON] Прячем эмитентов без имени, кол-во: ' . $issuersAmount . PHP_EOL;

        /** @var Issuer $issuer */
        foreach ($issuers->each() as $issuer) {
            try {
                $issuer->hide();
            } catch (\Throwable $e) {
                $errorClass = get_class($e);
                Yii::info("[CRON][ERROR] Прячем эмитентов без имени, ошибка: {$errorClass} {$e->getMessage()}");
                echo "[CRON][ERROR] Прячем эмитентов без имени, ошибка: {$errorClass} {$e->getMessage()}";
            }
        }

        return ExitCode::OK;
    }
}
