<?php

namespace src\Integration\Egr\Address;

use lib\ApiIntegrator\HttpMethod;
use lib\UrlGenerator;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Egr\EgrHttpClient;

class EgrAddressFetcher
{
    public function __construct(
        private EgrHttpClient $egrHttpClient,
        private UrlGenerator $urlGenerator,
    ) {
    }

    public function get(PayerIdentificationNumber $pid): EgrAddressDto
    {
        $dto = $this->egrHttpClient->request(
            dtoClass: EgrAddressDto::class,
            method: HttpMethod::GET,
            path: sprintf(EgrHttpClient::CURRENT_ADDRESS, $pid->id)
        );

        $dto->issuerSite = $dto->issuerSite ? $this->urlGenerator->addProtocolIfNeeded($dto->issuerSite) : null;

        return $dto;
    }
}