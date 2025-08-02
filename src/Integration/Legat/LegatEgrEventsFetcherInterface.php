<?php

namespace src\Integration\Legat;

use src\Entity\Issuer\PayerIdentificationNumber;
use src\Integration\Legat\Dto\egrEventDto\LegatEgrEventsDto;

interface LegatEgrEventsFetcherInterface
{
    public function getEvents(PayerIdentificationNumber $pid): LegatEgrEventsDto;
}