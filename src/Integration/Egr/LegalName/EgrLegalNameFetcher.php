<?php

namespace src\Integration\Egr\LegalName;

use lib\ApiIntegrator\HttpMethod;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Egr\EgrHttpClient;

class EgrLegalNameFetcher
{
    public const PATH = '/v2/egr/getShortInfoByRegNum/%s';

    public function __construct(
        private EgrHttpClient $egrHttpClient,
    ) {
    }

    public function get(PayerIdentificationNumber $pid): EgrLegalNameDto
    {
        return $this->egrHttpClient->request(EgrLegalNameDto::class, HttpMethod::GET, sprintf(self::PATH, $pid->id));
    }
}