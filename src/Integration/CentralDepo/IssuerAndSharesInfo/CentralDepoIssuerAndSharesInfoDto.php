<?php

namespace src\Integration\CentralDepo\IssuerAndSharesInfo;

use lib\Helper\EmptyValueChecker;
use src\Entity\Issuer\IssuerLegalStatus;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

class CentralDepoIssuerAndSharesInfoDto
{
    public function __construct(
        public string $fullName,
        #[NotBlank]
        public string $shortName,
        public string $issuerCode,
        #[NotBlank]
        public string $pid,
        public string $address,
        public ?string $phone = null,
        public string $depo,
        public float $authorizedCapital,
        public IssuerLegalStatus $legalStatus,
        #[Valid]
        /** @var ShareInfoDto[] */
        public array $shareDtos = [],
    ) {
        if (EmptyValueChecker::isEmpty($this->phone)) {
            $this->phone = null;
        }
    }
}