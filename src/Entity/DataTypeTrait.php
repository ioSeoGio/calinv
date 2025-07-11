<?php

namespace src\Entity;

/**
 * @property string $_dataType
 * @property DataTypeEnum $dataType
 */
trait DataTypeTrait
{
    public function getDataType(): DataTypeEnum
    {
        return DataTypeEnum::from($this->_dataType);
    }

    public function setDataType(DataTypeEnum $dataType): void
    {
        $this->_dataType = $dataType->value;
    }
}