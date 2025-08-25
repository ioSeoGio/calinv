<?php

namespace lib\CsvExporter;

use src\Entity\Issuer\FinanceReport\FinancialReportInterface;

class FinancialReportsCsvExporter
{
    public static function export(FinancialReportInterface ...$financialReports): string
    {
        if (empty($financialReports)) {
            return '';
        }

        $bom = "\xEF\xBB\xBF";
        $delimiter = ';';
        $newLine = "\n";

        /** @var FinancialReportInterface $report */
        $report = reset($financialReports);

        $headerRow = "Описание$delimiter" . "Код строки$delimiter";
        foreach ($financialReports as $financialReport) {
            $headerRow .= $financialReport->getYear()->format('Y') . $delimiter;
        }
        $headerRow .= $newLine;

        $rows = '';
        foreach ($report::getAttributesToShow() as $attributeGroupName => $attributes) {
            $rows .= $attributeGroupName . $newLine;
            foreach ($attributes as $attributeRowName => $attributeName) {
                $rows .= $attributeName . $delimiter;
                $rows .= str_replace('_', '', $attributeRowName) . $delimiter;
                foreach ($financialReports as $financialReport) {
                    $rows .= ($financialReport->$attributeRowName ?? '') . $delimiter;
                }
                $rows .= $newLine;
            }
        }

        return $bom . $headerRow . $rows;
    }
}