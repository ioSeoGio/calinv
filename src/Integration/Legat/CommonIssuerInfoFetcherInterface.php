<?php

namespace src\Integration\Legat;

use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Legat\Dto\CommonIssuerInfo\CommonIssuerInfoDto;

interface CommonIssuerInfoFetcherInterface
{
    public function getCommonInfo(PayerIdentificationNumber $pid): CommonIssuerInfoDto;
}