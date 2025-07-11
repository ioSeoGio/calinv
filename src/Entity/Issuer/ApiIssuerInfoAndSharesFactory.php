<?php

namespace src\Entity\Issuer;

use Psr\Log\LogLevel;
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
            $issuer->save();

            foreach ($dto->shareDtos as $shareDto) {
                $this->shareFactory->create($shareDto, $issuer, false);
            }
        } catch (\Throwable $e) {
            Yii::getLogger()->log($e->getMessage(), LogLevel::ERROR);
        } finally {
            FullnessStateChecker::update($issuer);
            $issuer->save();
        }
    }
}