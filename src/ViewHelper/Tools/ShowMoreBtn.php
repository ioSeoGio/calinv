<?php

namespace src\ViewHelper\Tools;

use yii\helpers\Html;

class ShowMoreBtn
{
    public static function renderBtn(string $text, string $targetId): string
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