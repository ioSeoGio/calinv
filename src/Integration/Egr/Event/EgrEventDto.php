<?php

namespace src\Integration\Egr\Event;

use src\Integration\Egr\EgrLegalStatus;
use Symfony\Component\Serializer\Annotation\SerializedPath;
use Symfony\Component\Validator\Constraints as Assert;

// nsi00212R - орган принявший решение
// nsi00212 - текущий орган учета
class EgrEventDto
{
    #[Assert\NotBlank]
    #[SerializedPath('[ngr04004]')]
    public ?string $id;

    #[Assert\NotBlank]
    #[SerializedPath('[ngrn]')]
    public ?string $pid = null;

    #[Assert\NotBlank]
    #[SerializedPath('[dfrom]')]
    public ?string $dateFrom = null;

    #[SerializedPath('[dto]')]
    public ?string $dateTo = null;

    #[Assert\NotBlank]
    #[SerializedPath('[ddoc]')]
    public ?string $documentAppliedDate = null;

    #[Assert\NotBlank]
    #[SerializedPath('[nsi00212][vnuzp]')]
    public ?string $currentAccountingAgency = null;

    #[SerializedPath('[nsi00212R][vnuzp]')]
    public ?string $decideAccountingAgency = null;

    #[Assert\NotBlank]
    #[SerializedPath('[nsi00213][vnosn]')]
    public ?string $reason = null;

    #[Assert\NotBlank]
    #[SerializedPath('[nsi00223][vnop]')]
    public ?string $eventName = null;
}