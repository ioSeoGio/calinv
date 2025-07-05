<?php

namespace src\Entity\Issuer;

use src\Action\Issuer\IssuerCreateForm;
use src\Entity\Issuer\AddressInfo\ApiAddressInfoFactory;
use src\Entity\Issuer\TypeOfActivity\ApiTypeOfActivityFactory;

class IssuerFactory
{
    public function __construct(
        private ApiIssuerInfoAndSharesFactory $apiIssuerInfoAndSharesFactory,
        private ApiAddressInfoFactory $apiAddressInfoFactory,
        private ApiTypeOfActivityFactory $apiTypeOfActivityFactory,
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

        $this->apiIssuerInfoAndSharesFactory->update($issuer);
        $this->apiAddressInfoFactory->createOrUpdate($issuer);
        $this->apiTypeOfActivityFactory->createOrUpdate($issuer);

        return $issuer;
    }
}