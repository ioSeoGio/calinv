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

        return self::internalRender(
            $value,
            $formattedValue,
            $value,
            true
        );
    }

    public static function renderValue(mixed $value): string
    {
        return self::internalRender(
            $value,
            $value,
            $value,
            true,
        );
    }

    public static function renderValueColored(mixed $value): string
    {
        return $value === null
            ? ''
            : self::internalRender($value, $value, "<b style='color: dodgerblue'>$value</b>", false);
    }

    private static function internalRender(mixed $value, string $label, string $successText, bool $useTooltip): string
    {
        return ClipboardJsWidget::widget([
            'text' => $value,
            'tag' => 'span',
            'htmlOptions' => array_merge([
                'class' => 'btn btn-m btn-default ' . ($useTooltip ? 'click-tooltip' : ''),
                'type' => 'span',
            ], ($useTooltip ? ['title' => 'Скопировано!'] : [])),
            'label' => $label,
            'successText' => $successText,
        ]);
    }
}
