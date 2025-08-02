<?php

namespace src\Entity\Issuer\EmployeeAmount;

use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\IssuerEvent\IssuerEvent;
use src\Integration\Legat\Dto\EmployeeAmount\EmployeeAmountDto;

class EmployeeAmountRecordFactory
{
    public function createMany(Issuer $issuer, EmployeeAmountDto $employeeAmountDto): void
    {
        IssuerEvent::deleteAll();

        foreach ($employeeAmountDto->data as $record) {
            $employeeAmountRecord = EmployeeAmountRecord::createOrUpdate(
                issuer: $issuer,
                amount: $record->strength,
                year: $record->year,
                date: new \DateTimeImmutable($record->date),
            );
            $employeeAmountRecord->save();
        }
    }
}