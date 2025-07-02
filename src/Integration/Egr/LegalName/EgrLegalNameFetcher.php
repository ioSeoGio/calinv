<?php

namespace src\Integration\Egr\LegalName;

use lib\ApiIntegrator\HttpMethod;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Egr\EgrHttpClient;

class EgrLegalNameFetcher
{
    public function __construct(
        private EgrHttpClient $egrHttpClient,
    ) {
    }

    public function get(PayerIdentificationNumber $pid): EgrLegalNameDto
    {
        return $this->egrHttpClient->request(
            dtoClass: EgrLegalNameDto::class,
            method: HttpMethod::GET,
            path: sprintf(EgrHttpClient::SHORT_STATUS, $pid->id)
        );
    }
}