<?php

namespace src\Entity\Issuer;

use src\Action\Issuer\IssuerCreateForm;

class IssuerFactory
{
    public function __construct(
        private ApiIssuerInfoAndSharesFactory $apiIssuerInfoAndSharesFactory,
    ) {
    }

    public function createOrUpdateByForm(
        IssuerCreateForm $form,
    ): Issuer {
        return $this->createOrUpdate(new PayerIdentificationNumber($form->pid));
    }

    public function createOrUpdate(PayerIdentificationNumber $pid): Issuer
    {
        $issuer = Issuer::createOrGet($pid);
        $issuer->save();

        $this->apiIssuerInfoAndSharesFactory->update($issuer);

        return $issuer;
    }
}