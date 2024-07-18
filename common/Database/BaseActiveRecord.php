<?php

namespace common\Database;

use yii\mongodb\ActiveRecord;

class BaseActiveRecord extends ActiveRecord
{
    public function __get($name)
    {
        $reflection = new \ReflectionClass($this);
        try {
            foreach ($reflection->getAttributes() as $attribute) {
                if ($attribute->getArguments()['propertyName'] !== $name) {
                    continue;
                }

                $parentRawData = parent::__get($name);
                if ($attribute->getName() === EmbeddedOne::class) {
                    $object = new ($attribute->getArguments()['objectClass']);
                    foreach ($parentRawData as $key => $value) {
                        $object->$key = $value;
                    }
                    return $object;
                }

                if ($attribute->getName() === EmbeddedMany::class) {
                    $return = [];
                    foreach ($parentRawData as $one) {
                        $object = new ($attribute->getArguments()['objectClass']);
                        foreach ($one as $key => $value) {
                            $object->$key = $value;
                        }
                        $return[] = $object;
                    }
                    return $return;
                }
            }
        } catch (\Throwable $e) {
            return parent::__get($name);
        }

        return parent::__get($name);
    }
}
