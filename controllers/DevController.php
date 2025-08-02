<?php

namespace app\controllers;

use lib\BaseController;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Legat\CommonIssuerInfoFetcherInterface;

class DevController extends BaseController
{
    public $layout = 'main_borderless';

    public function __construct(
        $id,
        $module,
        private CommonIssuerInfoFetcherInterface $commonIssuerInfoFetcher,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $r = $this->commonIssuerInfoFetcher->getCommonInfo(new PayerIdentificationNumber('200050520'));
        dd($r);
    }
}