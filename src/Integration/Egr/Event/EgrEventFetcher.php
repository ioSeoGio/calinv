<?php

namespace src\Integration\Egr\Event;

use lib\ApiIntegrator\HttpMethod;
use src\Entity\Issuer\IssuerEvent\IssuerEvent;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Egr\EgrHttpClient;

class EgrEventFetcher
{
    public function __construct(
        private EgrHttpClient $egrHttpClient,
    ) {
    }

    public function update(PayerIdentificationNumber $pid): void
    {
        $dtos = $this->egrHttpClient->request(
            dtoClass: EgrEventDto::class . '[]',
            method: HttpMethod::GET,
            path: sprintf(EgrHttpClient::EVENTS, $pid->id)
        );

        /** @var EgrEventDto $dto */
        foreach ($dtos as $dto) {
            $issuerEvent = IssuerEvent::createOrUpdate(
                pid: new PayerIdentificationNumber($dto->pid),
                eventName: $dto->eventName ?: '-',
                eventDate: new \DateTimeImmutable($dto->dateFrom),
            );
            $issuerEvent->save();
        }
    }
}