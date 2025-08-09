<?php

namespace src\Integration\Bcse\ShareInfo;

class BcseShareFullInfoDto
{
    /** @var BcseShareDealRecordDto[] */
    public array $bcseShareDealRecordDtos = [];

    public function __construct(
        public BcseShareLastDealDto $bcseShareLastDealDto,
        BcseShareDealRecordDto ...$bcseShareDealRecordDtos,
    ) {
        $this->bcseShareDealRecordDtos = $bcseShareDealRecordDtos;
    }
}