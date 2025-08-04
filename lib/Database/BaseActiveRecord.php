<?php

namespace lib\Database;

use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

class BaseActiveRecord extends ActiveRecord
{
    public static function getOneById(int $id): static
    {
        $result = static::findOne($id);

        if ($result === null) {
            throw new NotFoundHttpException("Запись с id {$id} не найдена.");
        }

        return $result;
    }

    public static function getOneByCriteria(array $criteria = []): static
    {
        $result = static::findOne($criteria);

        if ($result === null) {
            throw new NotFoundHttpException("Запись не найдена.");
        }

        return $result;
    }
}