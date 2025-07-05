<?php

namespace src\Entity\Issuer\AddressInfo;

use src\Entity\Issuer\Helper\FullnessStateChecker;
use src\Entity\Issuer\Issuer;
use src\Integration\Egr\Address\EgrAddressFetcher;

class ApiAddressInfoFactory
{
    public function __construct(
        private EgrAddressFetcher $egrAddressFetcher,
    ) {
    }

    public function createOrUpdate(Issuer $issuer): void
    {
        try {
            $dto = $this->egrAddressFetcher->get($issuer->pid);
            $addressInfo = AddressInfo::createOrUpdate(
                pid: $issuer->pid,
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
    }
}