<?php

namespace src\Entity\Issuer;

use lib\Exception\UserException\ApiNotFoundException;
use lib\Exception\UserException\ApiSimpleBadResponseException;
use lib\Logger\ApplicationLogger;
use src\Entity\Issuer\AddressInfo\AddressInfo;
use src\Entity\Issuer\Helper\FullnessStateChecker;
use src\Entity\Share\ShareFactory;
use src\Integration\CentralDepo\CentralDepoIssuerAndShareInfoFetcher;
use yii\db\Exception;

class ApiIssuerInfoAndSharesFactory
{
    public function __construct(
        private CentralDepoIssuerAndShareInfoFetcher $centralDepoIssuerAndShareInfoFetcher,
        private ShareFactory $shareFactory,
    ) {
    }

    public function updateOnlyCentralDepo(Issuer $issuer): void
    {
        $dto = $this->centralDepoIssuerAndShareInfoFetcher->get($issuer->pid);
        $issuer->updateName($dto->shortName);
        $addressInfo = AddressInfo::createOrUpdate(
            pid: $issuer->pid,
            fullAddress: $dto->address,
            phoneNumbers: $dto->phone,
        );
        $addressInfo->save();

        foreach ($dto->shareDtos as $shareDto) {
            $this->shareFactory->create($shareDto, $issuer);
        }
    }

    /**
     * @throws ApiNotFoundException
     * @throws Exception
     * @throws ApiSimpleBadResponseException
     */
    public function fillFromBcse(Issuer $issuer): void
    {
        foreach ($issuer->getActiveShares()->each() as $share) {
            $this->shareFactory->fillFromBcse($share, $issuer);
        }
    }

    public function update(Issuer $issuer): void
    {
        $sharesWereOk = $issuer->hasShares() && !$issuer->hasState(IssuerFullnessState::sharesWithException);

        try {
            $dto = $this->centralDepoIssuerAndShareInfoFetcher->get($issuer->pid);
            $issuer->updateName(name: $dto->shortName);
            $addressInfo = AddressInfo::createOrUpdate(
                pid: $issuer->pid,
                fullAddress: $dto->address,
                phoneNumbers: $dto->phone,
            );
            $addressInfo->save();

            foreach ($dto->shareDtos as $shareDto) {
                $this->shareFactory->createWithBcse($shareDto, $issuer);
            }
        } catch (\Throwable $e) {
            ApplicationLogger::log($e);

            if (!$sharesWereOk) {
                $issuer->addFullnessState(IssuerFullnessState::sharesWithException);
                $issuer->save();
            }

//            return;
            throw $e;
        } finally {
            FullnessStateChecker::update($issuer);
            $issuer->markShareInfoNotModerated();
            $issuer->save();
        }

        /** При успешном обновлении убираем старые флаги про ошибки */
        $issuer->removeFullnessState(IssuerFullnessState::sharesWithException);
        $issuer->save();
    }
}