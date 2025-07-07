<?php

namespace app\widgets;

use lib\Helper\DetailViewCopyHelper;
use yii\base\Model;
use yii\grid\DataColumn;

class ShowCopyNumberColumn extends DataColumn
{
    public $format = null;

    /**
     * @param Model $model The data model being rendered.
     * @param string $key The key associated with the data model.
     * @param int $index The zero-based index of the data model in the data provider.
     * @return string The rendered content for the data cell.
     */
    protected function renderDataCellContent($model, $key, $index): string
    {
        return DetailViewCopyHelper::render($model, $this->attribute, $this->format);
    }
}