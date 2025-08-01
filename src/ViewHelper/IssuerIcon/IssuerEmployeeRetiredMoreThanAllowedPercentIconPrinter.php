<?php

namespace src\ViewHelper\IssuerIcon;

use lib\EnvGetter;
use lib\FrontendHelper\Icon;
use src\Entity\Issuer\EmployeeAmount\EmployeeAmountRecord;
use src\Entity\Issuer\Issuer;
use yii\helpers\Html;

class IssuerEmployeeRetiredMoreThanAllowedPercentIconPrinter
{
    public static function print(Issuer $issuer): string
    {
        /** @var EmployeeAmountRecord[] $employeeAmountRecords */
        $employeeAmountRecords = EmployeeAmountRecord::find()
            ->andWhere(['issuerId' => $issuer->id])
            ->addOrderBy(['_date' => SORT_ASC])
            ->all();

        if (empty($employeeAmountRecords) || count($employeeAmountRecords) < 2) {
            return '';
        }

        $allowedRetiredPercent = EnvGetter::getInt('EMPLOYEE_RETIRED_PERCENT_ALERT');
        $firstRecord = array_shift($employeeAmountRecords);

        $badSigns = [];
        foreach ($employeeAmountRecords as $employeeAmountRecord) {
            $employeeChange = $employeeAmountRecord->amount - $firstRecord->amount;
            if ($employeeChange < 0) {
                $retiredPercent = -1 * $employeeChange / $firstRecord->amount * 100;

                if ($retiredPercent >= $allowedRetiredPercent) {
                    $badSigns[$employeeAmountRecord->year] = $retiredPercent;
                }
            }

            $firstRecord = $employeeAmountRecord;
        }

        if (empty($badSigns)) {
            return '';
        }

        $title = "Эмитент сократил численность работников: ";
        foreach ($badSigns as $year => $retiredPercent) {
            $title .= round($retiredPercent) . "% в $year году";
            if (next($badSigns)) {
                $title .= ", ";
            }
        }

        return Html::tag(
            'span',
            Icon::print('bi bi-person-dash'),
            [
                'class' => 'btn btn-sm btn-outline-danger me-1',
                'title' => $title,
            ]
        );
    }
}