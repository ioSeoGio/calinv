<?php

namespace lib;

use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

class BaseActiveRecord extends ActiveRecord
{
    public static function getById(int $id): static
    {
        $result = self::findOne($id);

        if ($result === null) {
            throw new NotFoundHttpException();
        }

        return $result;
    }
}