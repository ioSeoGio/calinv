<?php

namespace src\Entity\Issuer\FinanceReport\CashFlowReport;

use src\Integration\Legat\Dto\FinanceReportCashFlowDto;

class CashFlowReportDto
{
    public function __construct(
        public float $_020,
        public float $_030,
        public float $_040,
        public float $_080,
        public float $_090,

        public float $_100,
        public float $_110,

        public ?float $_021 = null,
        public ?float $_022 = null,
        public ?float $_023 = null,
        public ?float $_024 = null,

        public ?float $_031 = null,
        public ?float $_032 = null,
        public ?float $_033 = null,

        public ?float $_050 = null,
        public ?float $_051 = null,
        public ?float $_052 = null,
        public ?float $_053 = null,
        public ?float $_054 = null,
        public ?float $_055 = null,

        public ?float $_060 = null,
        public ?float $_061 = null,
        public ?float $_062 = null,
        public ?float $_063 = null,
        public ?float $_064 = null,
        public ?float $_070 = null,

        public ?float $_081 = null,
        public ?float $_082 = null,
        public ?float $_083 = null,
        public ?float $_084 = null,

        public ?float $_091 = null,
        public ?float $_092 = null,
        public ?float $_093 = null,
        public ?float $_094 = null,
        public ?float $_095 = null,

        public ?float $_120 = null,
        public ?float $_130 = null,
        public ?float $_140 = null,
    ) {
    }

    public static function fromApiCurrentYear(FinanceReportCashFlowDto $dto): self
    {
        return new self(
            _020: $dto->_020,
            _021: $dto->_021,
            _022: $dto->_022,
            _023: $dto->_023,
            _024: $dto->_024,
            _030: $dto->_030,
            _031: $dto->_031,
            _032: $dto->_032,
            _033: $dto->_033,
            _040: $dto->_040,
            _050: $dto->_050,
            _051: $dto->_051,
            _052: $dto->_052,
            _053: $dto->_053,
            _054: $dto->_054,
            _055: $dto->_055,
            _060: $dto->_060,
            _061: $dto->_061,
            _062: $dto->_062,
            _063: $dto->_063,
            _064: $dto->_064,
            _070: $dto->_070,
            _080: $dto->_080 !== null ? $dto->_080 : 0,
            _081: $dto->_081,
            _082: $dto->_082,
            _083: $dto->_083,
            _084: $dto->_084,
            _090: $dto->_090,
            _091: $dto->_091,
            _092: $dto->_092,
            _093: $dto->_093,
            _094: $dto->_094,
            _095: $dto->_095,
            _100: $dto->_100,
            _110: $dto->_110,
            _120: $dto->_120,
            _130: $dto->_130,
            _140: $dto->_140,
        );
    }

    public static function fromApiLastYear(FinanceReportCashFlowDto $dto): self
    {
        return new self(
            _020: $dto->last_020,
            _021: $dto->last_021,
            _022: $dto->last_022,
            _023: $dto->last_023,
            _024: $dto->last_024,
            _030: $dto->last_030,
            _031: $dto->last_031,
            _032: $dto->last_032,
            _033: $dto->last_033,
            _040: $dto->last_040,
            _050: $dto->last_050,
            _051: $dto->last_051,
            _052: $dto->last_052,
            _053: $dto->last_053,
            _054: $dto->last_054,
            _055: $dto->last_055,
            _060: $dto->last_060,
            _061: $dto->last_061,
            _062: $dto->last_062,
            _063: $dto->last_063,
            _064: $dto->last_064,
            _070: $dto->last_070,
            _080: $dto->_080 !== null ? $dto->_080 : 0,
            _081: $dto->last_081,
            _082: $dto->last_082,
            _083: $dto->last_083,
            _084: $dto->last_084,
            _090: $dto->last_090,
            _091: $dto->last_091,
            _092: $dto->last_092,
            _093: $dto->last_093,
            _094: $dto->last_094,
            _095: $dto->last_095,
            _100: $dto->last_100,
            _110: $dto->last_110,
            _120: $dto->last_120,
            _130: $dto->last_130,
            _140: $dto->last_140,
        );
    }
}