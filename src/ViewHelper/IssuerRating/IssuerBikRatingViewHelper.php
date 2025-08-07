<?php

namespace src\ViewHelper\IssuerRating;

use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfo;
use src\Entity\Issuer\CreditRating\CreditRatingInfo;
use src\Entity\Issuer\EsgRating\EsgRatingInfo;
use src\Entity\Issuer\Issuer;
use src\Integration\Bik\CreditRating\BikCreditRatingForecast;
use src\ViewHelper\Tools\Badge;

class IssuerBikRatingViewHelper
{
    public static function render(Issuer $issuer): string
    {
        if ($issuer->businessReputationInfo !== null) {
            return self::renderBusinessReputation($issuer->businessReputationInfo, true);
        } elseif ($issuer->esgRatingInfo !== null) {
            return self::renderEsgRating($issuer->esgRatingInfo, true);
        } elseif ($issuer->creditRatingInfo !== null) {
            return self::renderCreditRating($issuer->creditRatingInfo, true);
        }

        return '';
    }

    public static function renderEsgRating(EsgRatingInfo $esgRatingInfo, bool $withDate): string
    {
        $result = '';
        $year = $esgRatingInfo->getLastUpdateDate()->format('m/Y');
        $rating = $esgRatingInfo->getRating();

        if ($withDate) {
            $result .= "$year: ";
            $result .= "<br>";
        }

        if ($rating->isGood()) {
            $result .= Badge::success($rating->value);
        } elseif ($rating->isOk()) {
            $result .= Badge::warning($rating->value);
        } elseif ($rating->isBad()) {
            $result .= Badge::danger($rating->value);
        } else {
            $result .= Badge::secondary($rating->value);
        }

        return $result;
    }

    public static function renderBusinessReputation(BusinessReputationInfo $businessReputationInfo, bool $withDate): string
    {
        $result = '';

        $year = $businessReputationInfo->getLastUpdateDate()->format('m/Y');
        $rating = $businessReputationInfo->getRating();

        if ($withDate) {
            $result .= "$year: ";
            $result .= "<br>";
        }

        if ($rating->isGood()) {
            $result .= Badge::success($rating->value);
        } elseif ($rating->isOk()) {
            $result .= Badge::warning($rating->value);
        } elseif ($rating->isBad()) {
            $result .= Badge::danger($rating->value);
        } else {
            $result .= Badge::secondary($rating->value);
        }

        return $result;
    }

    public static function renderCreditRatingForecast(CreditRatingInfo $creditRatingInfo, bool $withDate): string
    {
        if ($creditRatingInfo->getForecast() === null) {
            return '';
        }

        $result = '';

        $year = $creditRatingInfo->getLastUpdateDate()->format('m/Y');
        $rating = $creditRatingInfo->getForecast();

        if ($withDate) {
            $result .= "$year: ";
            $result .= "<br>";
        }

        if ($rating === BikCreditRatingForecast::positive) {
            $result .= Badge::success($rating->value);
        } elseif ($rating === BikCreditRatingForecast::uncertain) {
            $result .= Badge::warning($rating->value);
        } elseif ($rating === BikCreditRatingForecast::negative) {
            $result .= Badge::danger($rating->value);
        } else {
            $result .= Badge::secondary($rating->value);
        }

        return $result;
    }

    public static function renderCreditRating(CreditRatingInfo $creditRatingInfo, bool $withDate): string
    {
        $result = '';

        $year = $creditRatingInfo->getLastUpdateDate()->format('m/Y');
        $rating = $creditRatingInfo->getRating();

        if ($withDate) {
            $result .= "$year: ";
            $result .= "<br>";
        }

        if ($rating->isGood()) {
            $result .= Badge::success($rating->value);
        } elseif ($rating->isOk()) {
            $result .= Badge::warning($rating->value);
        } elseif ($rating->isBad()) {
            $result .= Badge::danger($rating->value);
        } else {
            $result .= Badge::secondary($rating->value);
        }

        return $result;
    }
}
