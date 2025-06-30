<?php

namespace src\Entity\Issuer;

enum IssuerFullnessState: string
{
    case initial = 'initial';
    case nameAndStatus = 'nameAndStatus';
}