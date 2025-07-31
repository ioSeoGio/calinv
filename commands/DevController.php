<?php

namespace app\commands;

use src\Entity\Issuer\PayerIdentificationNumber;
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
        private EgrAddressFetcher $egrAddressFetcher,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
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
}
