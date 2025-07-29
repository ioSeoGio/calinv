<?php

namespace src\Entity\Issuer;

use Psr\Log\LogLevel;
use src\Action\Issuer\IssuerCreateForm;
use src\Entity\Issuer\AddressInfo\ApiAddressInfoFactory;
use src\Entity\Issuer\TypeOfActivity\ApiTypeOfActivityFactory;
use src\Integration\Egr\Event\EgrEventFetcher;
use Yii;

class IssuerFactory
{
    public function __construct(
        private ApiIssuerInfoAndSharesFactory $apiIssuerInfoAndSharesFactory,
        private ApiAddressInfoFactory $apiAddressInfoFactory,
        private ApiTypeOfActivityFactory $apiTypeOfActivityFactory,
        private EgrEventFetcher $egrEventFetcher,
    ) {
    }

    public function createOrUpdate(
        IssuerCreateForm $form,
    ): Issuer {
        return $this->update(new PayerIdentificationNumber($form->pid));
    }

    public function update(PayerIdentificationNumber $pid): Issuer
    {
        $issuer = Issuer::createOrGet($pid);
        $issuer->save();

        $this->apiIssuerInfoAndSharesFactory->update($issuer);
        try {
            $this->apiAddressInfoFactory->createOrUpdate($issuer);
        } catch (\Throwable $e) {
            Yii::getLogger()->log($e->getMessage(), LogLevel::ERROR);
        }
        $this->apiTypeOfActivityFactory->createOrUpdate($issuer);
        $this->egrEventFetcher->update($issuer->pid);

        return $issuer;
    }
}