<?php

namespace src\Integration\Bik\CreditRating;

use lib\Helper\DateTimeHelper;
use lib\Helper\TrimHelper;
use lib\Transformer\IssuerNameTransformer;
use src\Entity\Issuer\CreditRating\CreditRating;
use src\Integration\Bik\BikEsgRating;
use src\Integration\Bik\CreditRating\BikCreditRatingForecast;

class BikCreditRatingDto
{
    public string $issuerName;
    public ?BikCreditRatingForecast $forecast;
    public CreditRating $rating;
    public \DateTimeImmutable $assignmentDate;
    public \DateTimeImmutable $lastUpdateDate;
    public string $pressReleaseLink;

    public function __construct(
        string $issuerName,
        ?string $forecast,
        string $rating,
        string $assignmentDate,
        string $lastUpdateDate,
        string $pressReleaseLink,
    ) {
        $this->issuerName = IssuerNameTransformer::transform($issuerName);

        $this->forecast = BikCreditRatingForecast::fromString($forecast);
        $this->rating = CreditRating::fromString($rating);
        $this->assignmentDate = DateTimeHelper::createFromShit('d.m.Y', $assignmentDate);
        $this->lastUpdateDate = DateTimeHelper::createFromShit('d.m.Y', $lastUpdateDate);
        $this->pressReleaseLink = TrimHelper::trim($pressReleaseLink);
    }
}