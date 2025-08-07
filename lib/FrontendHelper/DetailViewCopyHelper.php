<?php

namespace lib\FrontendHelper;

use supplyhog\ClipboardJs\ClipboardJsWidget;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

class DetailViewCopyHelper
{
    /**
     * Рендерит виджет для копирования значения атрибута.
     *
     * @param Model $model Модель
     * @param string $attribute Имя атрибута
     * @param string|null $format Формат для отображения значения (например, 'decimal')
     * @return string
     * @throws \Exception
     */
    public static function render(Model $model, string $attribute, ?string $format = null): string
    {
        $value = $model->{$attribute};

        if ($value === null) {
            return Yii::$app->formatter->nullDisplay;
        }

        // Форматируем значение, если указан формат
        $formattedValue = $format ? Yii::$app->formatter->format($value, $format) : Html::encode($value);

        return ClipboardJsWidget::widget([
            'text' => $value,
            'tag' => 'span',
            'htmlOptions' => ['class' => 'btn btn-m btn-default', 'type' => 'span', 'title' => ''],
            'label' => $formattedValue,
            'successText' => $formattedValue . ' <i class="bi bi-check2-all"></i>',
        ]);
    }

    public static function renderValue(mixed $value): string
    {
        return ClipboardJsWidget::widget([
            'text' => $value,
            'tag' => 'span',
            'htmlOptions' => ['class' => 'btn btn-m btn-default', 'type' => 'span', 'title' => ''],
            'label' => $value,
            'successText' => $value . ' <i class="bi bi-check2-all"></i>',
        ]);
    }

    public static function renderValueColored(mixed $value): string
    {
        return $value === null ? '' : ClipboardJsWidget::widget([
            'text' => $value,
            'tag' => 'span',
            'htmlOptions' => ['class' => 'btn btn-m btn-default', 'type' => 'span', 'title' => ''],
            'label' => $value,
            'successText' => "<b style='color: dodgerblue'>$value</b>",
        ]);
    }
}
