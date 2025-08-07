<?php

namespace src\Entity\Issuer\FinanceReport;

use src\Entity\Issuer\Issuer;

interface FinancialReportInterface
{
    /** @return string */
    public static function getFinancialAttributeLabel(string $attribute);
    public function getYear(): \DateTimeImmutable;
    public static function getAttributesToShow(): array;
}