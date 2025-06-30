<?php

namespace src\Entity\Share;

class ShareRegisterNumber
{
    public function __construct(
        public readonly string $number,
    ) {
    }
}