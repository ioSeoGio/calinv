<?php

namespace src\Entity\Issuer\FinanceReport\CashFlowReport;

use DateTimeImmutable;
use lib\Database\ApiFetchedActiveRecord;
use src\Entity\DataTypeEnum;
use src\Entity\DataTypeTrait;
use src\Entity\Issuer\FinanceTermType;
use src\Entity\Issuer\Issuer;
use src\Integration\Legat\Dto\FinanceReportCashFlowDto;
use yii\db\ActiveQuery;

/**
 * @inheritDoc
 *
 * @property FinanceTermType $termType
 * @property string $_termType
 *
 * @property string $_year
 * @property DateTimeImmutable $year
 *
 * Движение денежных средств - далее ДДС
 * Денежные средства - далее ДС
 *
 * ТЕКУЩАЯ ДЕЯТЕЛЬНОСТЬ
 * @property float $_020 Поступило по текущей деятельности (всего)
 * @property ?float $_021 в том числе: от покупателей продукции, товаров, заказчиков работ, услуг
 * @property ?float $_022 в том числе: от покупателей материалов и других запасов
 * @property ?float $_023 в том числе: роялти
 * @property ?float $_024 в том числе: прочие поступления
 *
 * @property float $_030 Направлено по текущей деятельности (всего)
 * @property ?float $_031 в том числе: на приобретение запасов, работ, услуг
 * @property ?float $_032 в том числе: на оплату труда
 * @property ?float $_033 в том числе: на прочие выплаты
 * @property float $_040 Результат ДДС по текущей деятельности
 *
 * ИНВЕСТИЦИОННАЯ ДЕЯТЕЛЬНОСТЬ
 * @property ?float $_050 Поступило по инвестиционной деятельности (всего)
 * @property ?float $_051 в том числе: от покупателей основных средств, нематериальных активов и других долгосрочных активов
 * @property ?float $_052 в том числе: возврат предоставленных займов
 * @property ?float $_053 в том числе: доходы от участия в уставных капиталах других организаций
 * @property ?float $_054 в том числе: проценты
 * @property ?float $_055 в том числе: прочие поступления
 *
 * @property ?float $_060 Направлено по инвестиционной деятельности (всего)
 * @property ?float $_061 в том числе: на приобретение и создание основных средств, нематериальных активов и других долгосрочных активов
 * @property ?float $_062 в том числе: на предоставление займов
 * @property ?float $_063 в том числе: на вклады в уставные капиталы других организаций
 * @property ?float $_064 в том числе: прочие выплаты
 * @property ?float $_070 Результат ДДС по инвестиционной деятельности
 *
 * ФИНАНСОВАЯ ДЕЯТЕЛЬНОСТЬ
 * @property float $_080 Поступило по финансовой деятельности (всего)
 * @property ?float $_081 в том числе: кредиты и займы
 * @property ?float $_082 в том числе: от выпуска акций
 * @property ?float $_083 в том числе: вклады собственника имущества (учредителей, участников)
 * @property ?float $_084 в том числе: прочие поступления
 *
 * @property float $_090 Направлено по финансовой деятельности (всего)
 * @property ?float $_091 в том числе: на погашение кредитов и займов
 * @property ?float $_092 в том числе: на выплаты дивидендов и других доходов от участия в уставном капитале организации
 * @property ?float $_093 в том числе: на выплаты процентов
 * @property ?float $_094 в том числе: на лизинговые платежи
 * @property ?float $_095 в том числе: прочие выплаты
 * @property float $_100 Результат ДДС по финансовой деятельности
 *
 * @property float $_110 Результат ДДС по текущей, инвестиционной и финансовой деятельности
 *
 * @property float $_120 Остаток ДС на (31.12.year-2)
 * @property float $_130 Остаток ДС на (31.12.year-1)
 * @property ?float $_140 Влияние изменений курсов иностранных валют
 *
 * @property Issuer $issuer
 */
class CashFlowReport extends ApiFetchedActiveRecord
{
    use DataTypeTrait;

    public static function tableName(): string
    {
        return 'issuer_cash_flow_report';
    }

    public function attributeLabels(): array
    {
        return [
            '_year' => 'Год',
            '_termType' => 'Тип отчета',
            '_040' => 'Результат ДДС по текущей деятельности',
            '_070' => 'Результат ДДС по инвестиционной деятельности',
            '_100' => 'Результат ДДС по финансовой деятельности',
            '_110' => 'Результат ДДС по текущей, инвестиционной и финансовой',
        ];
    }

    public static function createOrUpdate(
        Issuer $issuer,
        DateTimeImmutable $date,
        CashFlowReportDto $dto,
        DataTypeEnum $dataType,
        FinanceTermType $termType = FinanceTermType::year,
    ): self {
        $self = self::findOne([
            'issuer_id' => $issuer->id,
            '_year' => $date->format('Y'),
            '_termType' => $termType->value,
            '_dataType' => $dataType->value,
        ]) ?: new self([
            'issuer_id' => $issuer->id,
            '_year' => $date->format('Y'),
            '_termType' => $termType->value,
            '_dataType' => $dataType->value,
        ]);

        $self->updateFieldsFromDto($dto);
        $self->renewLastApiUpdateDate();

        return $self;
    }

    private function updateFieldsFromDto(CashFlowReportDto $dto): void
    {
        $this->_020 = $dto->_020;
        $this->_021 = $dto->_021;
        $this->_022 = $dto->_022;
        $this->_023 = $dto->_023;
        $this->_024 = $dto->_024;

        $this->_030 = $dto->_030;
        $this->_031 = $dto->_031;
        $this->_032 = $dto->_032;
        $this->_033 = $dto->_033;
        $this->_040 = $dto->_040;

        $this->_050 = $dto->_050;
        $this->_051 = $dto->_051;
        $this->_052 = $dto->_052;
        $this->_053 = $dto->_053;
        $this->_054 = $dto->_054;
        $this->_055 = $dto->_055;

        $this->_060 = $dto->_060;
        $this->_061 = $dto->_061;
        $this->_062 = $dto->_062;
        $this->_063 = $dto->_063;
        $this->_064 = $dto->_064;
        $this->_070 = $dto->_070;

        $this->_080 = $dto->_080;
        $this->_081 = $dto->_081;
        $this->_082 = $dto->_082;
        $this->_083 = $dto->_083;
        $this->_084 = $dto->_084;

        $this->_090 = $dto->_090;
        $this->_091 = $dto->_091;
        $this->_092 = $dto->_092;
        $this->_093 = $dto->_093;
        $this->_094 = $dto->_094;
        $this->_095 = $dto->_095;
        $this->_100 = $dto->_100;

        $this->_110 = $dto->_110;

        $this->_120 = $dto->_120;
        $this->_130 = $dto->_130;
        $this->_140 = $dto->_140;
    }

    public function getYear(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->_year);
    }

    public function getTermType(): FinanceTermType
    {
        return FinanceTermType::from($this->_termType);
    }

    public function getIssuer(): ActiveQuery
    {
        return $this->hasOne(Issuer::class, ['id' => 'issuer_id']);
    }
}
