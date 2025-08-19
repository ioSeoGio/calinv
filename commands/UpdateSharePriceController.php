<?php

namespace app\commands;

use lib\Exception\UserException\ApiLightTemporaryUnavailableException;
use src\Entity\Issuer\ApiIssuerInfoAndSharesFactory;
use src\Entity\Issuer\Issuer;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class UpdateSharePriceController extends Controller
{
    public function __construct(
        $id,
        $module,

        private ApiIssuerInfoAndSharesFactory $apiIssuerInfoAndSharesFactory,

        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionUpdateAll(): int
    {
        $issuers = Issuer::findVisible();

        Yii::info('[CRON] Обновление акций отображаемых эмитентов и цен акций');
        echo '[CRON] Обновление акций отображаемых эмитентов и цен акций, кол-во: ' . $issuers->count() . PHP_EOL;
        foreach ($issuers->each() as $issuer) {
            try {
                Yii::info("[CRON] Обновление акций эмитента $issuer->name, УНП: $issuer->_pid");
                echo "[CRON] Обновление акций эмитента $issuer->name, УНП: $issuer->_pid" . PHP_EOL;

                $this->apiIssuerInfoAndSharesFactory->update($issuer);
            } catch (ApiLightTemporaryUnavailableException $exception) {
                Yii::info("[CRON][ERROR] API временно недоступно для акций эмитента $issuer->name, УНП: $issuer->_pid");
                echo "[CRON][ERROR] API временно недоступно для акций эмитента $issuer->name, УНП: $issuer->_pid" . PHP_EOL;
            } catch (\Throwable $exception) {
                Yii::info("[CRON][ERROR] Обновление акций эмитента $issuer->name, УНП: $issuer->_pid: {$exception->getMessage()}");
                echo '[CRON][ERROR] ' . $exception->getMessage() . PHP_EOL;
            }
        }

        return ExitCode::OK;
    }
}
