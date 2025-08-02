<?php

namespace src\Integration\Legat;

use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Legat\Dto\EmployeeAmount\EmployeeAmountDto;

interface EmployeeAmountFetcherInterface
{
    public function getEmployeeAmount(PayerIdentificationNumber $pid): EmployeeAmountDto;
}