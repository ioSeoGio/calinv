<?php

namespace src\Entity\Issuer\FinanceReport;

use src\Entity\Issuer\Issuer;

interface FinancialReportInterface
{
    /** @return string */
    public function getAttributeLabel(string $attribute);
    public function getYear(): \DateTimeImmutable;
    public function getAttributesToShow(): array;
}