<?php

namespace src\ViewHelper\Tools;

use yii\helpers\Html;

class ShowMoreContainer
{
    public static function render(array $values, int $max = 5): string
    {
        $id = bin2hex(random_bytes(8));

        $visibleValues = array_slice($values, 0, $max);
        $hiddenValues = array_slice($values, $max);

        $result = implode('', $visibleValues);

        if (!empty($hiddenValues)) {
            $result .= self::renderBtn($id);
            $result .= self::renderContainer(
                implode('', $hiddenValues),
                $id
            );
        }

        return $result;
    }

    public static function renderBtn(string $targetId): string
    {
        return Html::tag('span', 'ещё', [
            'data-bs-target' => "#$targetId",
            'data-bs-toggle' => "collapse",
            'class' => 'badge bg-primary',
            'style' => "cursor: pointer; user-select: none;",
        ]);
    }

    public static function renderContainer(string $content, string $id): string
    {
        return Html::tag('div', $content, ['class' => 'collapse', 'id' => $id]);
    }
}