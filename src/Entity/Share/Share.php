<?php

namespace src\Entity\Share;

use DateTimeImmutable;
use lib\BaseActiveRecord;
use src\Entity\Issuer\Issuer;
use src\Integration\Bcse\ShareInfo\BcseShareLastDealDto;
use yii\db\ActiveQuery;

/**
 * @property int $id
 *
 * @property string $lastDealChangePercent Изменение цены из-за последней сделки в %
 * @property string $lastDealDate Дата последней сделки
 * @property float $currentPrice Цена по последней сделке на бирже (БВФБ)
 *
 * @property ShareFullnessState $fullnessStateEnum Заполненность акции данными
 * @property string $fullnessState Заполненность акции данными
 * @property int $issuer_id
 * @property Issuer $issuer
 *
 * @property string $nationalId Национальный id выпуска
 * @property int $orderedIssueId Порядкой номер выпуска
 * @property string $registerNumber Номер регистрации
 * @property ShareRegisterNumber $registerNumberObject Номер регистрации (объект)
 * @property float $denomination Номинал
 *
 * @property int $simpleIssuedAmount Кол-во обычных акций в выпуске
 * @property int $privilegedIssuedAmount Кол-во привелигерованных акций в выпуске
 * @property int $totalIssuedAmount Общее кол-во в выпуске
 *
 * @property string $issueDate Дата выпуска
 * @property string $closingDate Дата снятия с учета и хранения
 */
class Share extends BaseActiveRecord
{
    public static function tableName(): string
    {
        return 'share';
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Имя выпуска',
            'issuer_id' => 'Эмитент',
            'denomination' => 'Номинал',
            'currentPrice' => 'Текущая цена',
            'volumeIssued' => 'Объем выпуска',
        ];
    }

    public static function make(
        int $issuerId,
        string $nationalId,
        int $orderedIssueId,
        string $registerNumber,
        float $denominationPrice,
        int $simpleIssuedAmount,
        int $privilegedIssuedAmount,
        int $totalIssuedAmount,
        DateTimeImmutable $issueDate,
        ?DateTimeImmutable $closingDate,
    ): self {
        return new self([
            'issuer_id' => $issuerId,
            'fullnessState' => ShareFullnessState::initial->value,

            'nationalId' => $nationalId,
            'orderedIssueId' => $orderedIssueId,
            'registerNumber' => $registerNumber,
            'denomination' => $denominationPrice,
            'simpleIssuedAmount' => $simpleIssuedAmount,
            'privilegedIssuedAmount' => $privilegedIssuedAmount,
            'totalIssuedAmount' => $totalIssuedAmount,
            'issueDate' => $issueDate->format(DATE_ATOM),
            'closingDate' => $closingDate?->format(DATE_ATOM),
        ]);
    }


    public function setLastDealInfo(?BcseShareLastDealDto $lastDealDto): void
    {
        if ($lastDealDto === null) {
            $this->setFullnessState(ShareFullnessState::initial);
            return;
        }

        $this->currentPrice = $lastDealDto->price;
        $this->lastDealDate = $lastDealDto->date->format(DATE_ATOM);
        $this->lastDealChangePercent = $lastDealDto->changeFromPreviousDealPercent;
    }

    public function setFullnessState(ShareFullnessState $shareFullnessState): void
    {
        $this->fullnessState = $shareFullnessState->value;
    }

    public function getFullnessStateEnum(): ShareFullnessState
    {
        return ShareFullnessState::from($this->fullnessState);
    }

    public function getRegisterNumberObject(): ShareRegisterNumber
    {
        return new ShareRegisterNumber($this->registerNumber);
    }

    public function getIssuer(): ActiveQuery
    {
        return $this->hasOne(Issuer::class, ['id' => 'issuer_id']);
    }
}
