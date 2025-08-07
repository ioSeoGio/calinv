<?php

namespace src\Entity\Issuer\FinanceReport\ProfitLossReport;

use DateTimeImmutable;
use lib\Database\ApiFetchedActiveRecord;
use src\Entity\DataTypeEnum;
use src\Entity\DataTypeTrait;
use src\Entity\Issuer\FinanceReport\FinancialReportInterface;
use src\Entity\Issuer\FinanceTermType;
use src\Entity\Issuer\Issuer;
use src\Integration\Legat\Dto\FinanceReportProfitLossDto;
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
 * ОСНОВНАЯ ДЕЯТЕЛЬНОСТЬ
 * @property float $_010 Выручка от реализации продукции, товаров, работ, услуг
 * @property ?float $_020 Себестоимость реализованной продукции, товаров, работ, услуг
 * @property ?float $_030 Валовая прибыль
 * @property ?float $_040 Управленческие расходы
 * @property ?float $_050 Расходы на реализацию
 * @property ?float $_060 Прибыль (убыток) от реализации продукции, товаров, работ, услуг
 * @property ?float $_070 Прочие доходы по текущей деятельности
 * @property ?float $_080 Прочие расходы по текущей деятельности
 * @property float $_090 Прибыль (убыток) от текущей деятельности
 *
 * ИНВЕСТИЦИОННАЯ ДЕЯТЕЛЬНОСТЬ
 * @property ?float $_100 Доходы по инвестиционной деятельности:
 * @property ?float $_101 В том числе: доходы от выбытия основных средств, нематериальных активов и других долгосрочных активов
 * @property ?float $_102 В том числе: доходы от участия в уставных капиталах других организаций
 * @property ?float $_103 В том числе: проценты к получению
 * @property ?float $_104 В том числе: прочие доходы по инвестиционной деятельности
 * @property ?float $_110 Расходы по инвестиционной деятельности
 * @property ?float $_111 В том числе: расходы от выбытия основных средств, нематериальных активов
 * @property ?float $_112 В том числе: прочие расходы по инвестиционной деятельности
 *
 * ФИНАНСОВАЯ ДЕЯТЕЛЬНОСТЬ
 * @property ?float $_120 Доходы по финансовой деятельности
 * @property ?float $_121 В том числе: курсовые разницы от пересчета активов и обязательств
 * @property ?float $_122 В том числе: прочие доходы по финансовой деятельности
 * @property ?float $_130 Расходы по финансовой деятельности
 * @property ?float $_131 В том числе: проценты к уплате
 * @property ?float $_132 В том числе: курсовые разницы от пересчета активов и обязательств
 * @property ?float $_133 В том числе: прочие расходы по финансовой деятельности
 *
 * ИТОГО
 * @property ?float $_140 Прибыль (убыток) от инвестиционной, финансовой и иной деятельности
 * @property ?float $_150 Прибыль (убыток) до налогообложения
 * @property ?float $_160 Налог на прибыль
 * @property ?float $_170 Изменение отложенных налоговых активов
 * @property ?float $_180 Изменение отложенных налоговых обязательств
 * @property ?float $_190 Прочие налоги и сборы, исчисляемые из прибыли (дохода)
 * @property float $_210 Чистая прибыль (убыток)
 * @property ?float $_220 Результат от переоценки долгосрочных активов, не включаемый в чистую прибыль (убыток)
 * @property ?float $_230 Результат от прочих операций, не включаемый в чистую прибыль (убыток)
 * @property float $_240 Совокупная прибыль (убыток)
 *
 * @property Issuer $issuer
 */
class ProfitLossReport extends ApiFetchedActiveRecord implements FinancialReportInterface
{
    use DataTypeTrait;

    public static function tableName(): string
    {
        return 'issuer_profit_loss_report';
    }

    public function attributeLabels(): array
    {
        $attributes = [];
        foreach ($this->financialAttributes() as $items) {
            $attributes = array_merge($attributes, $items);
        }

        return array_merge([
            '_year' => 'Год',
            '_termType' => 'Тип отчета',
        ], $attributes);
    }

    public function getAttributesToShow(): array
    {
        return $this->financialAttributes();
    }

    private function financialAttributes(): array
    {
        return [
            'ОСНОВНАЯ ДЕЯТЕЛЬНОСТЬ' => [
                '_010' => 'Выручка от реализации продукции, товаров, работ, услуг, т.р.',
                '_020' => 'Себестоимость реализованной продукции, товаров, работ, услуг, т.р.',
                '_030' => 'Валовая прибыль, т.р.',
                '_040' => 'Управленческие расходы, т.р.',
                '_050' => 'Расходы на реализацию, т.р.',
                '_060' => 'Прибыль (убыток) от реализации продукции, товаров, работ, услуг, т.р.',
                '_070' => 'Прочие доходы по текущей деятельности, т.р.',
                '_080' => 'Прочие расходы по текущей деятельности, т.р.',
                '_090' => 'Прибыль (убыток) от текущей деятельности, т.р.',
            ],

            'ИНВЕСТИЦИОННАЯ ДЕЯТЕЛЬНОСТЬ' => [
                '_100' => 'Доходы по инвестиционной деятельности:, т.р.',
                '_101' => 'В том числе: доходы от выбытия основных средств, нематериальных активов и других долгосрочных активов, т.р.',
                '_102' => 'В том числе: доходы от участия в уставных капиталах других организаций, т.р.',
                '_103' => 'В том числе: проценты к получению, т.р.',
                '_104' => 'В том числе: прочие доходы по инвестиционной деятельности, т.р.',

                '_110' => 'Расходы по инвестиционной деятельности, т.р.',
                '_111' => 'В том числе: расходы от выбытия основных средств, нематериальных активов, т.р.',
                '_112' => 'В том числе: прочие расходы по инвестиционной деятельности, т.р.',
            ],

            'ФИНАНСОВАЯ ДЕЯТЕЛЬНОСТЬ' => [
                '_120' => 'Доходы по финансовой деятельности, т.р.',
                '_121' => 'В том числе: курсовые разницы от пересчета активов и обязательств, т.р.',
                '_122' => 'В том числе: прочие доходы по финансовой деятельности, т.р.',

                '_130' => 'Расходы по финансовой деятельности, т.р.',
                '_131' => 'В том числе: проценты к уплате, т.р.',
                '_132' => 'В том числе: курсовые разницы от пересчета активов и обязательств, т.р.',
                '_133' => 'В том числе: прочие расходы по финансовой деятельности, т.р.',
            ],

            'ИТОГО' => [
                '_140' => 'Прибыль (убыток) от инвестиционной, финансовой и иной деятельности, т.р.',
                '_150' => 'Прибыль (убыток) до налогообложения, т.р.',
                '_160' => 'Налог на прибыль, т.р.',
                '_170' => 'Изменение отложенных налоговых активов, т.р.',
                '_180' => 'Изменение отложенных налоговых обязательств, т.р.',
                '_190' => 'Прочие налоги и сборы, исчисляемые из прибыли (дохода), т.р.',

                '_210' => 'Чистая прибыль (убыток), т.р.',
                '_220' => 'Результат от переоценки долгосрочных активов, не включаемый в чистую прибыль (убыток), т.р.',
                '_230' => 'Результат от прочих операций, не включаемый в чистую прибыль (убыток), т.р.',
                '_240' => 'Совокупная прибыль (убыток), т.р.',
            ],
        ];
    }

    public static function createOrUpdate(
        Issuer $issuer,
        DateTimeImmutable $date,
        ProfitLossReportDto $dto,
        DataTypeEnum $dataType,
        FinanceTermType $termType = FinanceTermType::year,
    ): self {
        $self = self::findOne([
            'issuer_id' => $issuer->id,
            '_year' => $date->format('Y'),
            '_termType' => $termType->value,
        ]) ?: new self([
            'issuer_id' => $issuer->id,
            '_year' => $date->format('Y'),
            '_termType' => $termType->value,
        ]);
        
        $self->_dataType = $dataType->value;

        $self->updateFieldsFromDto($dto);
        $self->renewLastApiUpdateDate();

        return $self;
    }

    private function updateFieldsFromDto(ProfitLossReportDto $dto): void
    {
        $this->_010 = $dto->_010;
        $this->_020 = $dto->_020;
        $this->_030 = $dto->_030;
        $this->_040 = $dto->_040;
        $this->_050 = $dto->_050;
        $this->_060 = $dto->_060;
        $this->_070 = $dto->_070;
        $this->_080 = $dto->_080;
        $this->_090 = $dto->_090;

        $this->_100 = $dto->_100;
        $this->_101 = $dto->_101;
        $this->_102 = $dto->_102;
        $this->_103 = $dto->_103;
        $this->_104 = $dto->_104;

        $this->_110 = $dto->_110;
        $this->_111 = $dto->_111;
        $this->_112 = $dto->_112;

        $this->_120 = $dto->_120;
        $this->_121 = $dto->_121;
        $this->_122 = $dto->_122;

        $this->_130 = $dto->_130;
        $this->_131 = $dto->_131;
        $this->_132 = $dto->_132;
        $this->_133 = $dto->_133;

        $this->_140 = $dto->_140;
        $this->_150 = $dto->_150;
        $this->_160 = $dto->_160;
        $this->_170 = $dto->_170;
        $this->_180 = $dto->_180;
        $this->_190 = $dto->_190;
        $this->_210 = $dto->_210;
        $this->_220 = $dto->_220;
        $this->_230 = $dto->_230;
        $this->_240 = $dto->_240;
    }

    public function getYear(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('Y', $this->_year);
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
