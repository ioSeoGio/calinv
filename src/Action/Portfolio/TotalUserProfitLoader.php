<?php

namespace src\Action\Portfolio;

use lib\FrontendHelper\SimpleNumberFormatter;
use src\Entity\PersonalShare\PersonalShare;

class TotalUserProfitLoader
{
    public static function load(int $userId): string
    {
        $r = PersonalShare::find()
            ->select('SUM((share."currentPrice" - personal_share."buyPrice") * personal_share."amount") as totalProfit')
            ->andWhere(['IS NOT', 'share."currentPrice"', null])
            ->andWhere(['user_id' => $userId])
            ->joinWith(['share'])
            ->asArray()
            ->column();

        return SimpleNumberFormatter::toView($r[0] ?? 0) . ' р.' ?? 'Неизвестно';
    }
}