<?php

namespace src\Entity\Issuer\EsgRating;

use src\Integration\Bik\EsgRating\EsgRatingDto;

class EsgRatingInfoFactory
{
    public function createOrUpdateMany(EsgRatingDto ...$dtos): void
    {
        foreach ($dtos as $dto) {
            $esgRatingInfo = EsgRatingInfo::createOrUpdate(
                issuerName: $dto->issuerName,
                forecast: $dto->forecast,
                rating: $dto->rating->toEsgRating(),
                category: $dto->category,
                assignmentDate: $dto->assignmentDate,
                lastUpdateDate: $dto->lastUpdateDate,
                pressReleaseLink: $dto->pressReleaseLink,
            );
            $esgRatingInfo->save();
        }
    }
}