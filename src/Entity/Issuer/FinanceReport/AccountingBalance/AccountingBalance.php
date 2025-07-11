<?php

namespace src\Entity\Issuer\FinanceReport\AccountingBalance;

use DateTimeImmutable;
use lib\Database\ApiFetchedActiveRecord;
use src\Entity\DataTypeEnum;
use src\Entity\DataTypeTrait;
use src\Entity\Issuer\FinanceTermType;
use src\Entity\Issuer\Issuer;
use src\Integration\FinanceReport\Dto\FinanceReportAccountingBalanceDto;
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
 * ДОЛГОСРОЧНЫЕ АКТИВЫ
 * @property ?float $_110 Основные средства
 * @property ?float $_120 Нематериальные активы
 * @property ?float $_130 Доходные вложения в материальные активы:
 * @property ?float $_131 в том числе: инвестиционная недвижимость
 * @property ?float $_140 Вложения в долгосрочные активы
 * @property ?float $_150 Долгосрочные финансовые вложения
 * @property ?float $_160 Вложения в долгосрочные активы
 * @property ?float $_170 долгосрочная дебиторская задолженность
 * @property ?float $_180 Прочие долгосрочные активы
 * @property float $_190 Долгосрочные активы (всего)
 *
 * КРАТКОСРОЧНЫЕ АКТИВЫ
 * @property ?float $_210 Запасы:
 * @property ?float $_211 в том числе: материалы
 * @property ?float $_213 в том числе: незавершенное производство
 * @property ?float $_214 в том числе: готовая продукция и товары
 * @property ?float $_215 в том числе: товары отгруженные
 * @property ?float $_230 Расходы будущих периодов
 * @property ?float $_240 НДС по приобретенным товарам, работам, услугам
 * @property ?float $_250 Краткосрочная дебиторская задолженность
 * @property ?float $_260 Краткосрочные финансовые вложения
 * @property ?float $_270 Денежные средства и эквиваленты
 * @property ?float $_280 Прочие краткосрочные активы
 * @property float $_290 Краткосрочные активы (всего)
 * @property float $_300 Баланс активов (190 + 290)
 *
 * КАПИТАЛ
 * @property ?float $_410 Уставный капитал
 * @property ?float $_440 Резервный капитал
 * @property ?float $_450 Добавочный капитал
 * @property ?float $_460 Нераспределенная прибыль (непокрытый убыток)
 * @property float $_490 Капитал (всего)
 *
 * ДОЛГОСРОЧНЫЕ ОБЯЗАТЕЛЬСТВА
 * @property ?float $_510 Долгосрочные кредиты и займы
 * @property ?float $_540 Доходы будущих периодов
 * @property float $_590 Долгосрочные обязательства (всего)
 *
 * КРАТКОСРОЧНЫЕ ОБЯЗАТЕЛЬСТВА
 * @property ?float $_610 Краткосрочные кредиты и займы
 * @property ?float $_620 Краткосрочная часть долгосрочных обязательств
 * @property ?float $_630 Краткосрочная кредиторская задолженность
 * @property ?float $_631 в том числе: поставщикам, подрядчикам, исполнителям
 * @property ?float $_632 в том числе: по авансам полученным
 * @property ?float $_633 в том числе: по налогам и сборам
 * @property ?float $_634 в том числе: по социальному страхованию и обеспечению
 * @property ?float $_635 в том числе: по оплате труда
 * @property ?float $_636 в том числе: по лизинговым платежам
 * @property ?float $_637 в том числе: собственнику имущества (учредителям, участникам)
 * @property ?float $_638 в том числе: прочим кредиторам
 * @property ?float $_650 Доходы будущих периодов
 * @property ?float $_670 Прочие краткосрочные обязательства
 * @property float $_690 Краткосрочные обязательства (всего)
 * @property float $_700 Итоговый баланс (всего)
 *
 * @property Issuer $issuer
 */
class AccountingBalance extends ApiFetchedActiveRecord
{
    use DataTypeTrait;

    public static function tableName(): string
    {
        return 'issuer_accounting_balance';
    }

    public function attributeLabels(): array
    {
        return [
            '_year' => 'Год',
            '_termType' => 'Тип отчета',
            '_190' => 'Долгосрочные активы, т.р.',
            '_290' => 'Краткосрочные активы, т.р.',
            '_590' => 'Долгосрочные долги, т.р.',
            '_690' => 'Краткосрочные долги, т.р.',
            '_490' => 'Капитал, т.р.',
        ];
    }

    public static function createOrUpdate(
        Issuer $issuer,
        DateTimeImmutable $date,
        FinanceReportAccountingBalanceDto $dto,
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

        $self->setFieldsFromDto($dto);
        $self->renewLastApiUpdateDate();

        return $self;
    }

    private function setFieldsFromDto(FinanceReportAccountingBalanceDto $dto): void
    {
        $this->_110 = $dto->_110;
        $this->_120 = $dto->_120;
        $this->_130 = $dto->_130;
        $this->_131 = $dto->_131;
        $this->_140 = $dto->_140;
        $this->_150 = $dto->_150;
        $this->_160 = $dto->_160;
        $this->_170 = $dto->_170;
        $this->_180 = $dto->_180;
        $this->_190 = $dto->_190;
        $this->_210 = $dto->_210;
        $this->_211 = $dto->_211;
        $this->_213 = $dto->_213;
        $this->_214 = $dto->_214;
        $this->_215 = $dto->_215;
        $this->_230 = $dto->_230;
        $this->_240 = $dto->_240;
        $this->_250 = $dto->_250;
        $this->_260 = $dto->_260;
        $this->_270 = $dto->_270;
        $this->_280 = $dto->_280;
        $this->_290 = $dto->_290;
        $this->_300 = $dto->_300;
        $this->_410 = $dto->_410;
        $this->_440 = $dto->_440;
        $this->_450 = $dto->_450;
        $this->_460 = $dto->_460;
        $this->_490 = $dto->_490;
        $this->_510 = $dto->_510;
        $this->_540 = $dto->_540;
        $this->_590 = $dto->_590;
        $this->_610 = $dto->_610;
        $this->_620 = $dto->_620;
        $this->_630 = $dto->_630;
        $this->_631 = $dto->_631;
        $this->_632 = $dto->_632;
        $this->_633 = $dto->_633;
        $this->_634 = $dto->_634;
        $this->_635 = $dto->_635;
        $this->_636 = $dto->_636;
        $this->_637 = $dto->_637;
        $this->_638 = $dto->_638;
        $this->_650 = $dto->_650;
        $this->_670 = $dto->_670;
        $this->_690 = $dto->_690;
        $this->_700 = $dto->_700;
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
