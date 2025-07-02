<?php

namespace lib\Database;

/**
 * @property string $_lastApiUpdateDate
 * @property \DateTimeImmutable $lastApiUpdateDate
 */
class ApiFetchedActiveRecord extends BaseActiveRecord
{
    public function getLastApiUpdateDate(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat(DATE_ATOM, $this->_lastApiUpdateDate);
    }

    public function renewLastApiUpdateDate(): void
    {
        $this->_lastApiUpdateDate = (new \DateTimeImmutable())->format(DATE_ATOM);
    }

    public static function getLastUpdateSessionDate(array $where = []): ?string
    {
        return static::find()
            ->select(['MAX("_lastApiUpdateDate")'])
            ->andFilterWhere($where)
            ->scalar();
    }
}