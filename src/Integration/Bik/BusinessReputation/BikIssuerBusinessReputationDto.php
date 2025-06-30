<?php

namespace src\Integration\Bik\BusinessReputation;

use lib\Helper\DateTimeHelper;
use lib\Helper\TrimHelper;
use lib\Transformer\IssuerNameTransformer;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Bik\BikBusinessReputation;


class BikIssuerBusinessReputationDto
{
    public string $issuerName;
    public PayerIdentificationNumber $pid;
    public \DateTimeImmutable $lastUpdateDate;
    public BikBusinessReputation $businessReputation;
    public string $pressReleaseLink;

    public function __construct(
        string $pid,
        string $issuerName,
        string $rating,
        string $lastUpdateDate,
        string $pressReleaseLink,
    ) {
        $this->issuerName = IssuerNameTransformer::transform($issuerName);
        $this->pressReleaseLink = TrimHelper::trim($pressReleaseLink);
        $this->pid = new PayerIdentificationNumber($pid);
        $this->lastUpdateDate = DateTimeHelper::createFromShit('d.m.Y', $lastUpdateDate);
        $this->businessReputation = BikBusinessReputation::fromString($rating);
    }
}