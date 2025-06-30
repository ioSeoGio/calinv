<?php

namespace src\Entity\Share;

use src\Entity\Issuer\Issuer;
use src\Integration\Bcse\ShareInfo\BcseShareInfoFetcher;
use src\Integration\CentralDepo\IssuerAndSharesInfo\ShareInfoDto;

class ShareFactory
{
    public function __construct(
        private BcseShareInfoFetcher $fetcher,
    ) {
    }

    public function create(ShareInfoDto $dto, Issuer $issuer): void
    {
        try {
            $share = Share::make(
                issuerId: $issuer->id,
                nationalId: $dto->nationalId,
                orderedIssueId: $dto->orderedIssueId,
                registerNumber: $dto->registerNumber,
                denominationPrice: $dto->denominationPrice,
                simpleIssuedAmount: $dto->simpleIssuedAmount,
                privilegedIssuedAmount: $dto->privilegedIssuedAmount,
                totalIssuedAmount: $dto->totalIssuedAmount,
                issueDate: $dto->issueDate,
                closingDate: $dto->closingDate,
            );
            $share->save();

            $shareInfoDto = $this->fetcher->get($issuer->pidObject, $share->registerNumberObject);
            $share->setLastDealInfo($shareInfoDto);
            $share->setFullnessState(ShareFullnessState::lastDeal);
            $share->save();
        } catch (\Throwable $e) {
            if (isset($share)) {
                $share->setFullnessState(ShareFullnessState::initial);
                $share->save();
            }

//            throw $e;
        }
    }
}