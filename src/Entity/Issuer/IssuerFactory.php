<?php

namespace src\Entity\Issuer;

use src\Action\Issuer\IssuerCreateForm;
use src\Entity\Issuer\AddressInfo\AddressInfo;
use src\Entity\Issuer\Helper\FullnessStateChecker;
use src\Entity\Issuer\TypeOfActivity\TypeOfActivity;
use src\Entity\Share\ShareFactory;
use src\Integration\CentralDepo\CentralDepoIssuerAndShareInfoFetcher;
use src\Integration\Egr\Address\EgrAddressFetcher;
use src\Integration\Egr\TypeOfActivity\EgrTypeOfActivityFetcher;

class IssuerFactory
{
    public function __construct(
        private CentralDepoIssuerAndShareInfoFetcher $centralDepoIssuerAndShareInfoFetcher,
        private EgrAddressFetcher $egrAddressFetcher,
        private EgrTypeOfActivityFetcher $egrTypeOfActivityFetcher,
        private ShareFactory $shareFactory,
    ) {
    }

    public function createOrUpdate(
        IssuerCreateForm $form,
    ): Issuer {
        $issuer = Issuer::createOrGet(new PayerIdentificationNumber($form->pid));

        try {
            $dto = $this->centralDepoIssuerAndShareInfoFetcher->get($issuer->pid);
            $issuer->updateInfo(
                name: $dto->shortName,
                legalStatus: $dto->legalStatus,
            );
            $issuer->save();

            foreach ($dto->shareDtos as $shareDto) {
                $this->shareFactory->create($shareDto, $issuer);
            }
        } finally {
            FullnessStateChecker::update($issuer);
            $issuer->save();
        }

        try {
            $dto = $this->egrAddressFetcher->get($issuer->pid);
            $addressInfo = AddressInfo::createOrUpdate(
                pid: new PayerIdentificationNumber($form->pid),
                country: $dto->country,
                settlementType: $dto->settlementType,
                settlementName: $dto->settlementName,
                placeType: $dto->placeType,
                placeName: $dto->placeName,
                houseNumber: $dto->houseNumber,
                roomType: $dto->roomType,
                roomNumber: $dto->roomNumber,
                email: $dto->issuerEmail,
                site: $dto->issuerSite,
                phoneNumbers: $dto->issuerNumbers,
            );
            $addressInfo->save();
        } finally {
            FullnessStateChecker::update($issuer);
            $issuer->save();
        }

        try {
            $dto = $this->egrTypeOfActivityFetcher->get($issuer->pid);
            $typeOfActivity = TypeOfActivity::createOrUpdate(
                pid: new PayerIdentificationNumber($form->pid),
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

        return $issuer;
    }
}