<?php

namespace src\Entity\Issuer;

use src\Action\Issuer\IssuerCreateForm;
use src\Entity\Share\ShareFactory;
use src\Integration\CentralDepo\CentralDepoIssuerAndShareInfoFetcher;

class IssuerFactory
{
    public function __construct(
        private CentralDepoIssuerAndShareInfoFetcher $centralDepoIssuerAndShareInfoFetcher,
        private ShareFactory $shareFactory,
    ) {
    }

    public function create(
        IssuerCreateForm $form,
    ): Issuer {
        $issuer = Issuer::make(
            pid: new PayerIdentificationNumber($form->pid),
        );

        try {
            $dto = $this->centralDepoIssuerAndShareInfoFetcher->get($issuer->pid);
            $issuer->updateName($dto->shortName);
            $issuer->updateLegalStatus($dto->legalStatus);
            $issuer->addFullnessState(IssuerFullnessState::nameAndStatus);
            $issuer->save();

            foreach ($dto->shareDtos as $shareDto) {
                $this->shareFactory->create($shareDto, $issuer);
            }
        } catch (\Throwable $e) {
            $issuer->addFullnessState(IssuerFullnessState::initial);
            $issuer->save();

            throw $e;
        }

        $issuer->save();
        return $issuer;
    }
}