<?php

namespace src\Integration\Bcse\AllShareList;

use Symfony\Component\Serializer\Attribute\SerializedPath;
use Symfony\Component\Validator\Constraints\NotBlank;

class BcseShareBaseInfoList
{
    #[NotBlank]
    /** @var BcseShareBaseInfoFromList[] */
    public array $list;
}
