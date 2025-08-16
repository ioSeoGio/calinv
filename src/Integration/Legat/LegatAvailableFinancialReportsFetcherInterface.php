<?php

namespace src\Integration\Legat;

use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Legat\Dto\AvailableFinancialReports\AllAvailableFinancialReportsDto;

interface LegatAvailableFinancialReportsFetcherInterface
{
    public function getAvailableReports(PayerIdentificationNumber $pid): AllAvailableFinancialReportsDto;
}