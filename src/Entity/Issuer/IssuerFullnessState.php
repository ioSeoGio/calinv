<?php

namespace src\Entity\Issuer;

enum IssuerFullnessState: string
{
    case initial = 'initial';
    case nameAndStatus = 'nameAndStatus';
    case shares = 'shares';
    case addressInfo = 'addressInfo';
}