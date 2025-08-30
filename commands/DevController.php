<?php

namespace app\commands;

use lib\Telegram\Sender\TelegramTradingDayShareSender;
use src\Cron\CronTelegramTradingDayResultSender;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Entity\Share\Share;
use src\Entity\Share\ShareRegisterNumber;
use src\Integration\Bcse\ShareInfo\BcseShareInfoFetcher;
use src\Integration\CentralDepo\CentralDepoIssuerAndShareInfoFetcher;
use src\Integration\Egr\Address\EgrAddressFetcher;
use yii\console\Controller;
use yii\console\ExitCode;

class DevController extends Controller
{
    public function __construct(
                                                     $id,
                                                     $module,
        private CentralDepoIssuerAndShareInfoFetcher $fetcher,
        private EgrAddressFetcher                    $egrAddressFetcher,
        private BcseShareInfoFetcher                 $bcseShareInfoFetcher,
        private TelegramTradingDayShareSender        $telegramTradingDayResultSender,
        private CronTelegramTradingDayResultSender   $cronTelegramTradingDayResultSender,
                                                     $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionTest(): int
    {
        $this->cronTelegramTradingDayResultSender->sendMany();
        return ExitCode::OK;
    }

    public function actionTelegram(): int
    {
        $this->cronTelegramTradingDayResultSender->sendMany();
        return ExitCode::OK;
    }

    public function actionFill(): int
    {
        /** @var Share $share */
        foreach (Share::find()->each() as $share) {
            $share->name = $share->getFormattedNameWithIssuer();
            $share->save();
        }

        return ExitCode::OK;
    }

    public function actionCentralDepo(string $pid): int
    {
        $dto = $this->fetcher->get(new PayerIdentificationNumber($pid));
        print_r($dto);

        return ExitCode::OK;
    }

    public function actionEgr(string $pid): int
    {
        $dto = $this->egrAddressFetcher->get(new PayerIdentificationNumber($pid));
        print_r($dto);

        return ExitCode::OK;
    }

    public function actionBcse(string $pid, string $shareRegisterNumber): int
    {
        $dto = $this->bcseShareInfoFetcher->get(
            new PayerIdentificationNumber($pid),
            new ShareRegisterNumber($shareRegisterNumber),
        );
        print_r($dto);

        return ExitCode::OK;
    }
}
