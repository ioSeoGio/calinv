<?php

namespace src\Entity\Issuer\Helper;

use src\Entity\Issuer\Issuer;
use src\Entity\Issuer\IssuerFullnessState;

class FullnessStateChecker
{
    public static function update(Issuer $issuer): void
    {
        $states = [];

        /**
         * Сохраняем ошибки чтобы автоматом не затерло
         * Убираем только напрямую вручную
         */
        if ($issuer->hasState(IssuerFullnessState::sharesWithException)) {
            $states[] = IssuerFullnessState::sharesWithException;
        }

        if (!empty($issuer->name) && $issuer->legalStatus->isFilled()) {
            $states[] = IssuerFullnessState::nameAndStatus;
        }

        if (!empty($issuer->getShares()->count() > 0)) {
            $states[] = IssuerFullnessState::shares;
        }

        if ($issuer->addressInfo !== null) {
            $states[] = IssuerFullnessState::addressInfo;
        }

        $issuer->setFullnessState(...$states);
    }
}