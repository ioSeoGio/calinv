<?php

namespace src\Integration\CentralDepo\IssuerAndSharesInfo;

use DateTimeImmutable;
use lib\Helper\EmptyValueChecker;
use lib\Helper\TrimHelper;
use Symfony\Component\Validator\Constraints\NotBlank;

class ShareInfoDto
{
    public int $simpleIssuedAmount;
    public int $privilegedIssuedAmount;
    public ?DateTimeImmutable $closingDate = null;
    public DateTimeImmutable $issueDate;

    public function __construct(
        #[NotBlank]
        public string $nationalId,
        #[NotBlank]
        public int $orderedIssueId,
        string $issueDate,
        #[NotBlank]
        public string $registerNumber,
        #[NotBlank]
        public float $denominationPrice,
        #[NotBlank]
        public int $totalIssuedAmount,
        ?string $simpleIssuedAmount = null,
        ?string $privilegedIssuedAmount = null,
        ?string $closingDate = null,
    ) {
        $this->nationalId = TrimHelper::trim($nationalId);

        if (EmptyValueChecker::isEmpty($simpleIssuedAmount)) {
            $this->simpleIssuedAmount = 0;
        } else {
            $this->simpleIssuedAmount = (int) $simpleIssuedAmount;
        }
        
        $this->privilegedIssuedAmount = EmptyValueChecker::isEmpty($privilegedIssuedAmount) ? 0 : $privilegedIssuedAmount;
        $this->issueDate = DateTimeImmutable::createFromFormat('d.m.Y', $issueDate);

        if (EmptyValueChecker::isEmpty($closingDate)) {
            $this->closingDate = null;
        } else {
            $this->closingDate = DateTimeImmutable::createFromFormat('d.m.Y', $issueDate);
        }
    }
}