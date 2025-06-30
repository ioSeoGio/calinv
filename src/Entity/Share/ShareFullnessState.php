<?php

namespace src\Entity\Share;

enum ShareFullnessState: string
{
    case initial = 'initial';
    case lastDeal = 'lastDeal';
}