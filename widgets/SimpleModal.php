<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class SimpleModal extends Widget
{
    public string $title;
    public string $body;
    public ?string $modalClass = null;
    public string $id;

    public function run()
    {
        return $this->render('simple-modal', [
            'class' => $this->modalClass,
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
        ]);
    }

    public static function renderButton(string $name, string $id): string
    {
        return Html::button($name, [
            'type' => 'button',
            'class' => 'btn btn-primary',
            'onclick' => "$('#$id').modal('toggle')",
        ]);
    }
}
