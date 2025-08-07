<?php

namespace src\Entity\Issuer\BusinessReputationRating;

use src\Integration\Bik\BusinessReputation\BikIssuerBusinessReputationDto;

class BusinessReputationInfoFactory
{
    public function createOrUpdateMany(BikIssuerBusinessReputationDto ...$dtos): void
    {
        foreach ($dtos as $dto) {
            if ($businessIssuerInfo = BusinessReputationInfo::findByPid($dto->pid)) {
                $businessIssuerInfo->updateInfo(
                    issuerName: $dto->issuerName,
                    rating: $dto->businessReputation->toIssuerBusinessReputation(),
                    expirationDate:  $dto->expirationDate,
                    pressReleaseLink: $dto->pressReleaseLink,
                );
                $businessIssuerInfo->save();
            } else {
                $this->create($dto);
            }
        }
    }

    public function create(BikIssuerBusinessReputationDto $dto): void
    {
        $model = BusinessReputationInfo::make(
            issuerName: $dto->issuerName,
            pid:  $dto->pid,
            rating: $dto->businessReputation->toIssuerBusinessReputation(),
            expirationDate:  $dto->expirationDate,
            pressReleaseLink:  $dto->pressReleaseLink,
        );
        $model->save();
    }
}