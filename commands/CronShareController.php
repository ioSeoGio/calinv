<?php

namespace app\commands;

use lib\Exception\UserException\ApiLightTemporaryUnavailableException;
use src\Entity\Issuer\ApiIssuerInfoAndSharesFactory;
use src\Entity\Issuer\Issuer;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class CronShareController extends Controller
{
    public function __construct(
        $id,
        $module,

        private ApiIssuerInfoAndSharesFactory $apiIssuerInfoAndSharesFactory,

        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionUpdateCentralDepo(): int
    {
        $issuers = Issuer::findVisible();

        Yii::info('[CRON][CENTRALDEPO] Обновление акций отображаемых эмитентов и цен акций');
        echo '[CRON][CENTRALDEPO] Обновление акций отображаемых эмитентов и цен акций, кол-во: ' . $issuers->count() . PHP_EOL;
        foreach ($issuers->each() as $issuer) {
            try {
                Yii::info("[CRON][CENTRALDEPO] Обновление акций эмитента $issuer->name, УНП: $issuer->_pid");
                echo "[CRON][CENTRALDEPO] Обновление акций эмитента $issuer->name, УНП: $issuer->_pid" . PHP_EOL;

                $this->apiIssuerInfoAndSharesFactory->updateOnlyCentralDepo($issuer);
            } catch (ApiLightTemporaryUnavailableException $exception) {
                Yii::info("[CRON][CENTRALDEPO][ERROR] API временно недоступно для акций эмитента $issuer->name, УНП: $issuer->_pid");
                echo "[CRON][CENTRALDEPO][ERROR] API временно недоступно для акций эмитента $issuer->name, УНП: $issuer->_pid" . PHP_EOL;
            } catch (\Throwable $exception) {
                Yii::info("[CRON][CENTRALDEPO][ERROR] Обновление акций эмитента $issuer->name, УНП: $issuer->_pid: {$exception->getMessage()}");
                echo '[CRON][CENTRALDEPO][ERROR] ' . $exception->getMessage() . PHP_EOL;
            }
        }

        return ExitCode::OK;
    }

    public function actionUpdateBcse(): int
    {
        $issuers = Issuer::findVisible();

        Yii::info('[CRON][BCSE] Обновление акций отображаемых эмитентов и цен акций');
        echo '[CRON][BCSE] Обновление акций отображаемых эмитентов и цен акций, кол-во: ' . $issuers->count() . PHP_EOL;
        foreach ($issuers->each() as $issuer) {
            try {
                Yii::info("[CRON][BCSE] Обновление акций эмитента $issuer->name, УНП: $issuer->_pid");
                echo "[CRON][BCSE] Обновление акций эмитента $issuer->name, УНП: $issuer->_pid" . PHP_EOL;

                $this->apiIssuerInfoAndSharesFactory->fillFromBcse($issuer);
            } catch (ApiLightTemporaryUnavailableException $exception) {
                Yii::info("[CRON][BCSE][ERROR] API временно недоступно для акций эмитента $issuer->name, УНП: $issuer->_pid");
                echo "[CRON][BCSE][ERROR] API временно недоступно для акций эмитента $issuer->name, УНП: $issuer->_pid" . PHP_EOL;
            } catch (\Throwable $exception) {
                Yii::info("[CRON][BCSE][ERROR] Обновление акций эмитента $issuer->name, УНП: $issuer->_pid: {$exception->getMessage()}");
                echo '[CRON][BCSE][ERROR] ' . $exception->getMessage() . PHP_EOL;
            }
        }

        return ExitCode::OK;
    }
}
