<?php

namespace lib\Helper;

use DateInterval;

class DateRounder
{
    public static function roundDateInterval(DateInterval $interval): DateInterval
    {
        if ($interval->invert === 0) {
            return $interval;
        }

        if ($interval->i >= 45) {
            $interval->h++;
        }
        $interval->i = 0;

        if ($interval->h >= 18) {
            $interval->d++;
        }
        $interval->h = 0;

        if ($interval->d >= 20) {
            $interval->m++;
        }
        $interval->d = 0;

        if ($interval->m >= 9) {
            $interval->y++;
        }
        $interval->m = 0;

        return $interval;
    }
}
