<?php

namespace src\Entity\Issuer\BusinessReputationRating;

use src\Entity\Issuer\Issuer;
use src\Integration\Bik\BusinessReputation\BikIssuerBusinessReputationDto;

class BusinessReputationInfoFactory
{
    public function createOrUpdateMany(BikIssuerBusinessReputationDto ...$dtos): void
    {
        foreach ($dtos as $dto) {
            $model = BusinessReputationInfo::createOrUpdate(
                issuer: Issuer::findByPid($dto->pid),
                issuerName: $dto->issuerName,
                pid: $dto->pid,
                rating: $dto->businessReputation->toIssuerBusinessReputation(),
                lastUpdateDate: $dto->lastUpdateDate,
                pressReleaseLink: $dto->pressReleaseLink,
            );
            $model->save();
        }
    }
}