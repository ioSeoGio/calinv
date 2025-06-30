<?php

namespace src\Entity\Issuer;

/**
 * Учетный номер плательщика (УНП)
 */
class PayerIdentificationNumber
{
    public function __construct(
        public readonly string $id,
    ) {
    }
}