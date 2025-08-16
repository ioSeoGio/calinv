<?php

namespace src\Integration\Legat\Dto\AvailableFinancialReports;

use Symfony\Component\Serializer\Annotation\SerializedPath;

class AvailableFinancialReportsDto
{
    public int $year;

    #[SerializedPath('[block]')]
    public bool $isBlocked;

    public ?array $methods = [];

    public function hasAccountingBalance(): bool
    {
        return $this->methods !== null && in_array('financeBalance', $this->methods);
    }

    public function hasProfitLossReport(): bool
    {
        return $this->methods !== null && in_array('financeProfitLoss', $this->methods);
    }

    public function hasCapitalChangeReport(): bool
    {
        return $this->methods !== null && in_array('financeCapital', $this->methods);
    }

    public function hasCashFlowReport(): bool
    {
        return $this->methods !== null && in_array('financeTraffic', $this->methods);
    }
}
