<?php

namespace src\Entity\Share;

use src\Entity\Issuer\Issuer;
use src\Entity\Share\Deal\ShareDealRecord;
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
            $share = Share::createOrUpdate(
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

            if ($share->isActive()) {
                $shareInfoDto = $this->fetcher->get($issuer->pid, $share->registerNumberObject);

                if ($shareInfoDto === null) {
                    return;
                }

                $share->setLastDealInfo($shareInfoDto->bcseShareLastDealDto);
                $share->setFullnessState(ShareFullnessState::lastDeal);
                $share->save();

                foreach ($shareInfoDto->bcseShareDealRecordDtos as $bcseShareDealRecordDto) {
                    $dealRecord = ShareDealRecord::createOrUpdate(
                        share: $share,
                        date: $bcseShareDealRecordDto->date,
                        currency: $bcseShareDealRecordDto->currency,
                        minPrice: $bcseShareDealRecordDto->minPrice,
                        maxPrice: $bcseShareDealRecordDto->maxPrice,
                        weightedAveragePrice: $bcseShareDealRecordDto->weightedAveragePrice,
                        totalSum: $bcseShareDealRecordDto->totalSum,
                        totalAmount: $bcseShareDealRecordDto->totalAmount,
                        totalDealAmount: $bcseShareDealRecordDto->totalDealAmount,
                    );
                    $dealRecord->save();
                }
                $share->countBoundaryPrice();
                $share->save();
            }
        } catch (\Throwable $e) {
            if (isset($share)) {
                $share->setFullnessState(ShareFullnessState::initial);
                $share->save();
            }

            throw $e;
        }
    }
}