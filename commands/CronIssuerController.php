<?php

namespace app\commands;

use lib\Exception\UserException\ApiLightTemporaryUnavailableException;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\IssuerFactory;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Entity\Share\ShareRegisterNumber;
use src\Integration\Bcse\AllShareList\BcseAllActiveShareFetcher;
use src\Integration\Bcse\AllShareList\BcseShareBaseInfoFromList;
use src\Integration\Bcse\ShareInfo\BcseShareInfoFetcher;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class CronIssuerController extends Controller
{
    public function __construct(
        $id,
        $module,

        private BcseAllActiveShareFetcher $bcseAllActiveShareFetcher,
        private BcseShareInfoFetcher $bcseShareInfoFetcher,
        private IssuerFactory $issuerFactory,

        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionCheckNew(): int
    {
        $dto = $this->bcseAllActiveShareFetcher->get();

        $issuersAmount = count($dto->list);
        Yii::info('[CRON][BCSE] Добавление новых эмитентов, кол-во: ' . $issuersAmount);
        echo '[CRON][BCSE] Добавление новых эмитентов, кол-во: ' . $issuersAmount . PHP_EOL;

        /** @var BcseShareBaseInfoFromList $shareDto */
        foreach ($dto->list as $shareDto) {
            echo PHP_EOL;

            try {
                Yii::info("[CRON][BCSE][$shareDto->pid] Начинаем проверку эмитента");
                echo "[CRON][BCSE][$shareDto->pid] Начинаем проверку эмитента" . PHP_EOL;

                $pid = new PayerIdentificationNumber($shareDto->pid);

                if (Issuer::findByPid($pid)) {
                    Yii::info("[CRON][BCSE][$shareDto->pid] Эмитент уже добавлен");
                    echo "[CRON][BCSE][$shareDto->pid] Эмитент уже добавлен" . PHP_EOL;
                    continue;
                }

                $shareFullInfoDto = $this->bcseShareInfoFetcher->get(
                    $pid,
                    new ShareRegisterNumber($shareDto->registerNumber)
                );
                if ($shareFullInfoDto === null) {
                    Yii::info("[CRON][BCSE][$shareDto->pid] Эмитент не торговался уже год");
                    echo "[CRON][BCSE][$shareDto->pid] Эмитент не торговался уже год" . PHP_EOL;
                    continue;
                }

                $this->issuerFactory->createOrUpdate($pid);

                Yii::info("[CRON][BCSE][$shareDto->pid] Добавлен эмитент");
                echo "[CRON][BCSE][$shareDto->pid] Добавлен эмитент" . PHP_EOL;
            } catch (ApiLightTemporaryUnavailableException $e) {
                Yii::info("[CRON][BCSE][ERROR] Добавление нового эмитента с УНП: $shareDto->pid, API временно недоступно");
                echo "[CRON][BCSE][ERROR] Добавление нового эмитента с УНП: $shareDto->pid, API временно недоступно\n";
            } catch (\Throwable $e) {
                $errorClass = get_class($e);
                Yii::info("[CRON][BCSE][ERROR] Добавление нового эмитента с УНП: $shareDto->pid, ошибка: {$errorClass} {$e->getMessage()}");
                echo "[CRON][BCSE][ERROR] Добавление нового эмитента с УНП: $shareDto->pid, ошибка: {$errorClass} {$e->getMessage()}";
            }
        }

        return ExitCode::OK;
    }
}
