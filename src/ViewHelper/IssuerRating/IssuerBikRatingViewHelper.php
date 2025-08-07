<?php

namespace src\ViewHelper\IssuerRating;

use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfo;
use src\Entity\Issuer\EsgRating\EsgRatingInfo;
use src\Entity\Issuer\Issuer;
use src\ViewHelper\Tools\Badge;

class IssuerBikRatingViewHelper
{
    public static function render(Issuer $issuer): string
    {
        if ($issuer->businessReputationInfo !== null) {
            return self::renderBusinessReputation($issuer->businessReputationInfo, true);
        } elseif ($issuer->esgRatingInfo !== null) {
            return self::renderEsgRating($issuer->esgRatingInfo, true);
        }

        return '';
    }

    public static function renderEsgRating(EsgRatingInfo $esgRatingInfo, bool $withDate): string
    {
        $result = '';
        $year = $esgRatingInfo->assignmentDate->format('m/Y');
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
            $result .= Badge::neutral($rating->value);
        }

        return $result;
    }

    public static function renderBusinessReputation(BusinessReputationInfo $businessReputationInfo, bool $withDate): string
    {
        $result = '';

        $year = $businessReputationInfo->getAssignmentDate()->format('m/Y');
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
            $result .= Badge::neutral($rating->value);
        }

        return $result;
    }
}
