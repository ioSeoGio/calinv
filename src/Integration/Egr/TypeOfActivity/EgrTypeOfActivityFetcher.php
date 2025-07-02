<?php

namespace src\Integration\Egr\TypeOfActivity;

use lib\ApiIntegrator\HttpMethod;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Egr\EgrHttpClient;

class EgrTypeOfActivityFetcher
{
    public function __construct(
        private EgrHttpClient $egrHttpClient,
    ) {
    }

    public function get(PayerIdentificationNumber $pid): EgrTypeOfActivityDto
    {
        $dto = $this->egrHttpClient->request(
            dtoClass: EgrTypeOfActivityDto::class,
            method: HttpMethod::GET,
            path: sprintf(EgrHttpClient::CURRENT_TYPE_OF_ACTIVITY, $pid->id)
        );

        $dto->isActive = $dto->cact === '+';

        return $dto;
    }
}