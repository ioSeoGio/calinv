<?php

namespace src\Entity\Issuer;

use src\Action\Issuer\IssuerCreateForm;

class IssuerFactory
{
    public function __construct(
        private ApiIssuerInfoAndSharesFactory $apiIssuerInfoAndSharesFactory,
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

        return $issuer;
    }
}