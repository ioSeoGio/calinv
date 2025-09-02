<?php

namespace src\Entity\Issuer\AdditionalInfo;

use src\Entity\Issuer\AddressInfo\AddressInfo;
use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\IssuerLegalStatus;
use src\Integration\Legat\Dto\CommonIssuerInfo\CommonIssuerInfoDto;
use Yii;

class ApiLegatCommonInfoFactory
{
    public function update(Issuer $issuer, CommonIssuerInfoDto $commonIssuerInfoDto): void
    {
        if ($commonIssuerInfoDto->error !== null) {
            Yii::$app->session->addFlash('error', 'Ошибка при обновлении: ' . $commonIssuerInfoDto->error);
            return;
        }

        if ($issuer->name === null) {
            $issuer->name = $commonIssuerInfoDto->detailsDto->shortIssuerName;
        }

        $site = $commonIssuerInfoDto->directorInfo?->site
            ? str_replace(' ', '', $commonIssuerInfoDto->directorInfo?->site)
            : null;
        if ($issuer->addressInfo !== null) {
            $issuer->addressInfo->updateFields(
                fullAddress: $commonIssuerInfoDto->detailsDto->fullAddress,
                phoneNumbers: $commonIssuerInfoDto->directorInfo?->phone,
                email: $commonIssuerInfoDto->directorInfo?->email,
                site: $site,
            );
            $issuer->addressInfo->save();
        } else {
            $addressInfo = AddressInfo::createOrUpdate(
                pid: $issuer->pid,
                fullAddress: $commonIssuerInfoDto->detailsDto->fullAddress,
                phoneNumbers: $commonIssuerInfoDto->directorInfo?->phone,
                email: $commonIssuerInfoDto->directorInfo?->email,
                site: $site,
            );
            $addressInfo->save();
        }

        if ($commonIssuerInfoDto->liquidation !== null) {
            $liquidation = $commonIssuerInfoDto->liquidation;
            $liquidationInfo = IssuerLiquidationInfo::createOrUpdate(
                issuer: $issuer,
                beginDate: new \DateTimeImmutable($liquidation->beginDate),
                publicationDate: new \DateTimeImmutable($liquidation->publicationDate),
                currentStatusClaimDate: $liquidation->currentStatusClaimDate !== null
                    ? new \DateTimeImmutable($liquidation->currentStatusClaimDate)
                    : null,
                liquidationDecisionNumber: $liquidation->liquidationDecisionNumber,
                liquidatorName: $liquidation->liquidatorName,
                liquidatorAddress: $liquidation->liquidatorAddress,
                liquidatorPhone: $liquidation->liquidatorPhones,
                periodForAcceptingClaimsInMonths: $liquidation->periodForAcceptingClaimsInMonths,
                status: $liquidation->status,
            );
            $liquidationInfo->save();
        }

        $additionalInfo = IssuerAdditionalInfo::createOrUpdate(
            issuer: $issuer,
            dto: $commonIssuerInfoDto,
        );
        $additionalInfo->save();
    }
}