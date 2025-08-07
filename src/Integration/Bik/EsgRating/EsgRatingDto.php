<?php

namespace src\Integration\Bik\EsgRating;

use lib\Helper\DateTimeHelper;
use lib\Helper\TrimHelper;
use lib\Transformer\IssuerNameTransformer;
use src\Integration\Bik\BikEsgRating;

class EsgRatingDto
{
    public string $issuerName;
    public BikEsgForecast $forecast;
    public BikEsgRating $rating;
    public EsgIssuerCategory $category;
    public \DateTimeImmutable $assignmentDate;
    public \DateTimeImmutable $expirationDate;
    public string $pressReleaseLink;

    public function __construct(
        string $issuerName,
        string $forecast,
        string $rating,
        string $category,
        string $assignmentDate,
        string $expirationDate,
        string $pressReleaseLink,
    ) {
        $this->issuerName = IssuerNameTransformer::transform($issuerName);

        $this->forecast = BikEsgForecast::fromString($forecast);
        $this->rating = BikEsgRating::fromString($rating);
        $this->category = EsgIssuerCategory::fromString($category);
        $this->assignmentDate = DateTimeHelper::createFromShit('d.m.Y', $assignmentDate);
        $this->expirationDate = DateTimeHelper::createFromShit('d.m.Y', $expirationDate);
        $this->pressReleaseLink = TrimHelper::trim($pressReleaseLink);
    }
}