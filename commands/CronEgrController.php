<?php

namespace app\commands;

use lib\Exception\UserException\ApiLightTemporaryUnavailableException;
use src\Entity\Issuer\Issuer;
use src\Integration\Egr\Event\EgrEventFetcher;
use src\Integration\Egr\LegalName\EgrLegalNameFetcher;
use src\Integration\Egr\TypeOfActivity\EgrTypeOfActivityFetcher;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class CronEgrController extends Controller
{
    public function __construct(
        $id,
        $module,

        private EgrTypeOfActivityFetcher $egrTypeOfActivityFetcher,
        private EgrLegalNameFetcher $egrLegalNameFetcher,
        private EgrEventFetcher $egrEventFetcher,

        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionIssuerEvent(): int
    {
        $issuers = Issuer::findVisible();

        $issuersAmount = $issuers->count();
        Yii::info('[CRON][EGR] Обновление событий эмитентов, кол-во: ' . $issuersAmount);
        echo '[CRON][EGR] Обновление событий эмитентов, кол-во: ' . $issuersAmount . PHP_EOL;

        /** @var Issuer $issuer */
        foreach ($issuers->each() as $issuer) {
            try {
                Yii::info('[CRON][EGR] Обновление событий эмитента с УНП: ' . $issuer->_pid);
                echo '[CRON][EGR] Обновление событий эмитента с УНП: ' . $issuer->_pid . PHP_EOL;

                $this->egrEventFetcher->update($issuer->pid);
            } catch (ApiLightTemporaryUnavailableException $e) {
                Yii::info("[CRON][EGR][ERROR] Обновление событий эмитента с УНП: $issuer->_pid, API временно недоступно");
                echo "[CRON][EGR][ERROR] Обновление событий эмитента с УНП: $issuer->_pid, API временно недоступно\n";
            } catch (\Throwable $e) {
                $errorClass = get_class($e);
                Yii::info("[CRON][EGR][ERROR] Обновление событий эмитента с УНП: $issuer->_pid, ошибка: {$errorClass} {$e->getMessage()}");
                echo "[CRON][EGR][ERROR] Обновление событий эмитента с УНП: $issuer->_pid, ошибка: {$errorClass} {$e->getMessage()}";
            }
        }

        return ExitCode::OK;
    }

    public function actionLegalStatus(): int
    {
        $issuers = Issuer::findVisible();

        $issuersAmount = $issuers->count();
        Yii::info('[CRON][EGR] Обновление статуса эмитентов, кол-во: ' . $issuersAmount);
        echo '[CRON][EGR] Обновление статуса эмитентов, кол-во: ' . $issuersAmount . PHP_EOL;

        /** @var Issuer $issuer */
        foreach ($issuers->each() as $issuer) {
            try {
                Yii::info('[CRON][EGR] Обновление статуса эмитента с УНП: ' . $issuer->_pid);
                echo '[CRON][EGR] Обновление статуса эмитента с УНП: ' . $issuer->_pid . PHP_EOL;

                $dto = $this->egrLegalNameFetcher->get($issuer->pid);
                $issuer->updateLegalStatus($dto->status->toLegalStatus());
                $issuer->save();
            } catch (ApiLightTemporaryUnavailableException $e) {
                Yii::info("[CRON][EGR][ERROR] Обновление статуса эмитента с УНП: $issuer->_pid, API временно недоступно");
                echo "[CRON][EGR][ERROR] Обновление статуса эмитента с УНП: $issuer->_pid, API временно недоступно\n";
            } catch (\Throwable $e) {
                $errorClass = get_class($e);
                Yii::info("[CRON][EGR][ERROR] Обновление статуса эмитента с УНП: $issuer->_pid, ошибка: {$errorClass} {$e->getMessage()}");
                echo "[CRON][EGR][ERROR] Обновление статуса эмитента с УНП: $issuer->_pid, ошибка: {$errorClass} {$e->getMessage()}";
            }
        }

        return ExitCode::OK;
    }

    public function actionTypeOfActivity(): int
    {
        $issuers = Issuer::findVisible()
            ->andWhere([
                'OR',
                [
                    'typeOfActivityCode' => null,
                    'typeOfActivity' => null,
                ],
            ]);

        $issuersAmount = $issuers->count();
        Yii::info('[CRON][EGR] Обновление ОКЭД отображаемых эмитентов, кол-во: ' . $issuersAmount);
        echo '[CRON][EGR] Обновление ОКЭД отображаемых эмитентов, кол-во: ' . $issuersAmount . PHP_EOL;

        /** @var Issuer $issuer */
        foreach ($issuers->each() as $issuer) {
            try {
                Yii::info('[CRON][EGR] Обновление ОКЭД эмитента с УНП: ' . $issuer->_pid);
                echo '[CRON][EGR] Обновление ОКЭД эмитента с УНП: ' . $issuer->_pid . PHP_EOL;

                $dto = $this->egrTypeOfActivityFetcher->get($issuer->pid);
                $issuer->updateTypeOfActivity($dto);
                $issuer->save();
            } catch (ApiLightTemporaryUnavailableException $e) {
                Yii::info("[CRON][EGR][ERROR] Обновление ОКЭД эмитента с УНП: $issuer->_pid, API временно недоступно");
                echo "[CRON][EGR][ERROR] Обновление ОКЭД эмитента с УНП: $issuer->_pid, API временно недоступно\n";
            } catch (\Throwable $e) {
                $errorClass = get_class($e);
                Yii::info("[CRON][EGR][ERROR] Обновление ОКЭД эмитента с УНП: $issuer->_pid, ошибка: {$errorClass} {$e->getMessage()}");
                echo "[CRON][EGR][ERROR] Обновление ОКЭД эмитента с УНП: $issuer->_pid, ошибка: {$errorClass} {$e->getMessage()}";
            }
        }

        return ExitCode::OK;
    }
}
