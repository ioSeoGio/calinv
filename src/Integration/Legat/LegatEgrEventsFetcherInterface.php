<?php

namespace src\Integration\Legat;

use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Legat\Dto\EgrEventDto\LegatEgrEventsDto;

interface LegatEgrEventsFetcherInterface
{
    public function getEvents(PayerIdentificationNumber $pid): LegatEgrEventsDto;
}