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
    public \DateTimeImmutable $expirationDate;
    public BikBusinessReputation $businessReputation;
    public string $pressReleaseLink;

    public function __construct(
        string $pid,
        string $issuerName,
        string $rating,
        string $expirationDate,
        string $pressReleaseLink,
    ) {
        $this->issuerName = IssuerNameTransformer::transform($issuerName);
        $this->pressReleaseLink = TrimHelper::trim($pressReleaseLink);
        $this->pid = new PayerIdentificationNumber($pid);
        $this->expirationDate = DateTimeHelper::createFromShit('d.m.Y', $expirationDate);
        $this->businessReputation = BikBusinessReputation::fromString($rating);
    }
}