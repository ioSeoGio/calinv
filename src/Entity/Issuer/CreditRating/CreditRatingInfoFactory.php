<?php

namespace src\Entity\Issuer\CreditRating;

use src\Integration\Bik\CreditRating\BikCreditRatingDto;

class CreditRatingInfoFactory
{
    public function createOrUpdateMany(BikCreditRatingDto ...$dtos): void
    {
        foreach ($dtos as $dto) {
            $businessIssuerInfo = CreditRatingInfo::createOrUpdate(
                issuerName: $dto->issuerName,
                rating: $dto->rating,
                forecast: $dto->forecast,
                assignmentDate: $dto->lastUpdateDate,
                lastUpdateDate: $dto->lastUpdateDate,
                pressReleaseLink: $dto->pressReleaseLink,
            );
            $businessIssuerInfo->save();
        }
    }
}