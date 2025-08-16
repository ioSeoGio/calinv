<?php

namespace src\Integration\Legat\Dto\AvailableFinancialReports;

use Symfony\Component\Serializer\Annotation\SerializedPath;

class AllAvailableFinancialReportsDto
{
    public bool $isMock = false;

    #[SerializedPath('[error]')]
    public ?string $error = null;

    #[SerializedPath('[result]')]
    /** @var AvailableFinancialReportsDto[] */
    public array $records = [];
}