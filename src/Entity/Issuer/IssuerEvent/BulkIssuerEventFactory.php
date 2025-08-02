<?php

namespace src\Entity\Issuer\IssuerEvent;

use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Legat\Dto\EgrEventDto\LegatEgrEventDto;
use src\Integration\Legat\LegatEgrEventsFetcherInterface;

class BulkIssuerEventFactory
{
    public function __construct(
        private LegatEgrEventsFetcherInterface $legatEgrEventsFetcher,
    ) {
    }

    public function update(PayerIdentificationNumber $pid): void
    {
        $eventsDto = $this->legatEgrEventsFetcher->getEvents($pid);

        /** @var LegatEgrEventDto $dto */
        foreach ($eventsDto->events as $dto) {
            $issuerEvent = IssuerEvent::createOrUpdate(
                pid: $pid,
                eventName: $dto->eventName,
                eventDate: new \DateTimeImmutable($dto->eventDate),
            );
            $issuerEvent->save();
        }
    }
}