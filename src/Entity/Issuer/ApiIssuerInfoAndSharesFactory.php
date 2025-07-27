<?php

namespace src\Entity\Issuer;

use lib\Logger\ApplicationLogger;
use src\Entity\Issuer\Helper\FullnessStateChecker;
use src\Entity\Share\ShareFactory;
use src\Integration\CentralDepo\CentralDepoIssuerAndShareInfoFetcher;
use Yii;

class ApiIssuerInfoAndSharesFactory
{
    public function __construct(
        private CentralDepoIssuerAndShareInfoFetcher $centralDepoIssuerAndShareInfoFetcher,
        private ShareFactory $shareFactory,
    ) {
    }

    public function update(Issuer $issuer): void
    {
        try {
            $dto = $this->centralDepoIssuerAndShareInfoFetcher->get($issuer->pid);
            $issuer->updateInfo(
                name: $dto->shortName,
                legalStatus: $dto->legalStatus,
            );

            foreach ($dto->shareDtos as $shareDto) {
                $this->shareFactory->create($shareDto, $issuer);
            }
        } catch (\Throwable $e) {
            ApplicationLogger::log($e);

            $issuer->addFullnessState(IssuerFullnessState::sharesWithException);
            $issuer->save();

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