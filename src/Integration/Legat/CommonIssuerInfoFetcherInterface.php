<?php

namespace src\Integration\Legat;

use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Legat\Dto\commonIssuerInfo\CommonIssuerInfoDto;

interface CommonIssuerInfoFetcherInterface
{
    public function getCommonInfo(PayerIdentificationNumber $pid): CommonIssuerInfoDto;
}