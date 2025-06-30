<?php

namespace src\Entity\Issuer\BusinessReputationRating;

enum IssuerBusinessReputation: string
{
    case AAA = 'AAA reputation';
    case AA = 'AA reputation';
    case A = 'A reputation';
    case B = 'B reputation';
    case C = 'C reputation';
    case D = 'D reputation';
    case Expired = 'Отозван';
    case Unknown = 'Неизвестный';
}