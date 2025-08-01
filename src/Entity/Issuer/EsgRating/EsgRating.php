<?php

namespace src\Entity\Issuer\EsgRating;

enum EsgRating: string
{
    case AAA = 'AAA.esg';
    case AAPlus = 'AA+.esg';
    case AA = 'AA.esg';
    case APlus = 'A+.esg';
    case A = 'A.esg';
    case BBB = 'BBB.esg';
    case BB = 'BB.esg';
    case B = 'B.esg';
    case CCC = 'CCC.esg';
    case CC = 'CC.esg';
    case C = 'C.esg';
    case Expired = 'Отозван';
    case Unknown = 'Неизвестен';
}