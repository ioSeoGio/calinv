<?php

namespace src\Entity\Issuer\TypeOfActivity;

use src\Entity\Issuer\Helper\FullnessStateChecker;
use src\Entity\Issuer\Issuer;
use src\Integration\Egr\TypeOfActivity\EgrTypeOfActivityFetcher;

class ApiTypeOfActivityFactory
{
    public function __construct(
        private EgrTypeOfActivityFetcher $egrTypeOfActivityFetcher,
    ) {
    }

    public function createOrUpdate(Issuer $issuer): void
    {
        try {
            $dto = $this->egrTypeOfActivityFetcher->get($issuer->pid);
            $typeOfActivity = TypeOfActivity::createOrUpdate(
                pid: $issuer->pid,
                activityFromDate: $dto->dateFrom,
                activityToDate: $dto->dateTo,
                isActive: $dto->isActive,
                code: $dto->typeOfActivityCode,
                name: $dto->typeOfActivityName,
            );
            $typeOfActivity->save();
        } finally {
            FullnessStateChecker::update($issuer);
            $issuer->save();
        }
    }
}