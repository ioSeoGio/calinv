<?php

namespace src\Entity\Issuer;

use src\Action\Issuer\IssuerCreateForm;
use src\Entity\Issuer\AdditionalInfo\ApiLegatCommonInfoFactory;
use src\Entity\Issuer\EmployeeAmount\EmployeeAmountRecordFactory;
use src\Entity\Issuer\FinanceReport\AccountingBalance\AccountingBalanceFactory;
use src\Entity\Issuer\FinanceReport\AvailableFinancialReportData;
use src\Entity\Issuer\FinanceReport\AvailableFinancialReportFactory;
use src\Entity\Issuer\FinanceReport\CashFlowReport\CashFlowReportFactory;
use src\Entity\Issuer\FinanceReport\ProfitLossReport\ProfitLossReportFactory;
use src\Entity\Issuer\IssuerEvent\BulkIssuerEventFactory;
use src\Integration\Legat\Api\LegatAvailableFinancialReportsFetcher;
use src\Integration\Legat\CommonIssuerInfoFetcherInterface;
use src\Integration\Legat\EmployeeAmountFetcherInterface;

class IssuerAutomaticLegatFactory
{
    public function __construct(
        private ApiIssuerInfoAndSharesFactory $apiIssuerInfoAndSharesFactory,
        private CommonIssuerInfoFetcherInterface $commonIssuerInfoFetcher,
        private ApiLegatCommonInfoFactory $apiLegatCommonInfoFactory,
        private BulkIssuerEventFactory $bulkIssuerEventFactory,
        private EmployeeAmountFetcherInterface $employeeAmountFetcher,
        private EmployeeAmountRecordFactory $employeeAmountRecordFactory,

        private LegatAvailableFinancialReportsFetcher $legatAvailableFinancialReportsFetcher,
        private AvailableFinancialReportFactory $availableFinancialReportFactory,

        private AccountingBalanceFactory $accountingBalanceFactory,
        private ProfitLossReportFactory $profitLossReportFactory,
        private CashFlowReportFactory $cashFlowReportFactory,
    ) {
    }

    public function createOrUpdate(
        IssuerCreateForm $form,
    ): Issuer {
        return $this->update(new PayerIdentificationNumber($form->pid));
    }

    public function update(PayerIdentificationNumber $pid): Issuer
    {
        $issuer = Issuer::createOrGet($pid);
        $issuer->save();

        $this->apiIssuerInfoAndSharesFactory->update($issuer);

        $dto = $this->commonIssuerInfoFetcher->getCommonInfo($issuer->getPid());
        $this->apiLegatCommonInfoFactory->update($issuer, $dto);

        $this->bulkIssuerEventFactory->update($issuer->pid);

        $dto = $this->employeeAmountFetcher->getEmployeeAmount($issuer->pid);
        $this->employeeAmountRecordFactory->createMany($issuer, $dto);

        $allDtos = $this->legatAvailableFinancialReportsFetcher->getAvailableReports($issuer->pid);
        $this->availableFinancialReportFactory->createOrUpdateBulk($issuer, $allDtos);

        $accountingBalanceAmount = 0;
        $profitLossAmount = 0;
        $cashFlowAmount = 0;
        /** @var AvailableFinancialReportData $availableFinancialReportData */
        foreach ($issuer->getLatestAvailableFinancialReportData()->each() as $availableFinancialReportData) {
            if ($availableFinancialReportData->hasAccountingBalance && $accountingBalanceAmount < 2) {
                $this->accountingBalanceFactory->createOrUpdateByExternalApi($issuer, $availableFinancialReportData->year);
                $accountingBalanceAmount++;
            }
            if ($availableFinancialReportData->hasProfitLossReport && $profitLossAmount < 2) {
                $this->profitLossReportFactory->createOrUpdateByExternalApi($issuer, $availableFinancialReportData->year);
                $profitLossAmount++;
            }
            if ($availableFinancialReportData->hasCashFlowReport && $cashFlowAmount < 2) {
                $this->cashFlowReportFactory->createOrUpdateByExternalApi($issuer, $availableFinancialReportData->year);
                $cashFlowAmount++;
            }

            break; // Пока заполняем только 2 года, ибо year должен прыгать через 2 года, а не через 1 (в запросе 2 года отдает)
        }

        return $issuer;
    }
}
