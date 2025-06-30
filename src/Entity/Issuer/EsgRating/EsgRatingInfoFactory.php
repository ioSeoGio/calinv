<?php

namespace src\Entity\Issuer\EsgRating;

use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfo;
use src\Entity\Issuer\Issuer;
use src\Integration\Bik\EsgRating\EsgRatingDto;

class EsgRatingInfoFactory
{
    public function createOrUpdateMany(EsgRatingDto ...$dtos): void
    {
        foreach ($dtos as $dto) {
            if ($esgRatingInfo = EsgRatingInfo::findByIssuerName($dto->issuerName)) {
                $pid = Issuer::findByIssuerName($dto->issuerName)?->pid;

                $esgRatingInfo->updateInfo(
                    pid: $pid,
                    forecast: $dto->forecast,
                    rating: $dto->rating->toEsgRating(),
                    category: $dto->category,
                    assignmentDate: $dto->assignmentDate,
                    lastUpdateDate: $dto->lastUpdateDate,
                    pressReleaseLink: $dto->pressReleaseLink,
                );
                $esgRatingInfo->save();
            } else {
                $this->create($dto);
            }
        }
    }

    public function create(EsgRatingDto $dto): void
    {
        $pid = BusinessReputationInfo::findByIssuerName($dto->issuerName)?->pid;

        $model = EsgRatingInfo::make(
            pid: $pid,
            issuerName: $dto->issuerName,
            forecast: $dto->forecast,
            rating: $dto->rating->toEsgRating(),
            category: $dto->category,
            assignmentDate: $dto->assignmentDate,
            lastUpdateDate:  $dto->lastUpdateDate,
            pressReleaseLink:  $dto->pressReleaseLink,
        );
        $model->save();
    }
}