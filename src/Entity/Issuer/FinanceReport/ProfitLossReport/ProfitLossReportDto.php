<?php

namespace src\Entity\Issuer\FinanceReport\ProfitLossReport;

use src\Integration\FinanceReport\Dto\FinanceReportProfitLossDto;

class ProfitLossReportDto
{
    public function __construct(
        public float $_010,
        public ?float $_020 = null,
        public ?float $_030 = null,
        public ?float $_040 = null,
        public ?float $_050 = null,
        public ?float $_060 = null,
        public ?float $_070 = null,
        public ?float $_080 = null,
        public ?float $_090 = null,

        public ?float $_100 = null,
        public ?float $_101 = null,
        public ?float $_102 = null,
        public ?float $_103 = null,
        public ?float $_104 = null,

        public ?float $_110 = null,
        public ?float $_111 = null,
        public ?float $_112 = null,

        public ?float $_120 = null,
        public ?float $_121 = null,
        public ?float $_122 = null,

        public ?float $_130 = null,
        public ?float $_131 = null,
        public ?float $_132 = null,
        public ?float $_133 = null,

        public ?float $_140 = null,
        public ?float $_150 = null,
        public ?float $_160 = null,
        public ?float $_170 = null,
        public ?float $_180 = null,
        public ?float $_190 = null,
        public float $_210,
        public ?float $_220 = null,
        public ?float $_230 = null,
        public float $_240,
    ) {
    }

    public static function fromApiCurrentYear(FinanceReportProfitLossDto $dto): self
    {
        return new self(
            _010: $dto->_010,
            _020: $dto->_020,
            _030: $dto->_030,
            _040: $dto->_040,
            _050: $dto->_050,
            _060: $dto->_060,
            _070: $dto->_070,
            _080: $dto->_080,
            _090: $dto->_090,
            _100: $dto->_100,
            _101: $dto->_101,
            _102: $dto->_102,
            _103: $dto->_103,
            _104: $dto->_104,
            _110: $dto->_110,
            _111: $dto->_111,
            _112: $dto->_112,
            _120: $dto->_120,
            _121: $dto->_121,
            _122: $dto->_122,
            _130: $dto->_130,
            _131: $dto->_131,
            _132: $dto->_132,
            _133: $dto->_133,
            _140: $dto->_140,
            _150: $dto->_150,
            _160: $dto->_160,
            _170: $dto->_170,
            _180: $dto->_180,
            _190: $dto->_190,
            _210: $dto->_210,
            _220: $dto->_220,
            _230: $dto->_230,
            _240: $dto->_240,
        );
    }

    public static function fromApiLastYear(FinanceReportProfitLossDto $dto): self
    {
        return new self(
            _010: $dto->last_010,
            _020: $dto->last_020,
            _030: $dto->last_030,
            _040: $dto->last_040,
            _050: $dto->last_050,
            _060: $dto->last_060,
            _070: $dto->last_070,
            _080: $dto->last_080,
            _090: $dto->last_090,
            _100: $dto->last_100,
            _101: $dto->last_101,
            _102: $dto->last_102,
            _103: $dto->last_103,
            _104: $dto->last_104,
            _110: $dto->last_110,
            _111: $dto->last_111,
            _112: $dto->last_112,
            _120: $dto->last_120,
            _121: $dto->last_121,
            _122: $dto->last_122,
            _130: $dto->last_130,
            _131: $dto->last_131,
            _132: $dto->last_132,
            _133: $dto->last_133,
            _140: $dto->last_140,
            _150: $dto->last_150,
            _160: $dto->last_160,
            _170: $dto->last_170,
            _180: $dto->last_180,
            _190: $dto->last_190,
            _210: $dto->last_210,
            _220: $dto->last_220,
            _230: $dto->last_230,
            _240: $dto->last_240,
        );
    }
}