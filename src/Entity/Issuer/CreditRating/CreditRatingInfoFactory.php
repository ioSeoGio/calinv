<?php

namespace src\Entity\Issuer\CreditRating;

use src\Integration\Bik\CreditRating\BikCreditRatingDto;

class CreditRatingInfoFactory
{
    public function createOrUpdateMany(BikCreditRatingDto ...$dtos): void
    {
        foreach ($dtos as $dto) {
            if ($businessIssuerInfo = CreditRatingInfo::findByIssuerName($dto->issuerName)) {
                $businessIssuerInfo->updateInfo(
                    issuerName: $dto->issuerName,
                    rating: $dto->rating,
                    forecast: $dto->forecast,
                    assignmentDate: $dto->lastUpdateDate,
                    lastUpdateDate: $dto->lastUpdateDate,
                    pressReleaseLink: $dto->pressReleaseLink,
                );
                $businessIssuerInfo->save();
            } else {
                $this->create($dto);
            }
        }
    }

    public function create(BikCreditRatingDto $dto): void
    {
        $model = CreditRatingInfo::make(
            issuerName: $dto->issuerName,
            rating: $dto->rating,
            forecast: $dto->forecast,
            assignmentDate: $dto->lastUpdateDate,
            lastUpdateDate: $dto->lastUpdateDate,
            pressReleaseLink: $dto->pressReleaseLink,
        );
        $model->save();
    }
}