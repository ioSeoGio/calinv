<?php

namespace common\DataProvider;

use yii\data\ArrayDataProvider;
use yii\mongodb\ActiveRecord;

class BaseArrayDataProvider extends ArrayDataProvider
{
    public function filter(string $property, mixed $value): void
    {
        foreach ($this->allModels as $key => $model) {
            if ($this->getProperty($model, $property) != $value) {
                unset($this->allModels[$key]);
            }
        }
    }

    private function getProperty(ActiveRecord $model, $propertyPath): mixed
    {
        $properties = explode('.', $propertyPath);
        $current = $model;

        foreach ($properties as $property) {
            $current = $current?->$property;
        }
        return $current;
    }
}
