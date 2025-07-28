<?php

namespace app\widgets;
use yii\base\Model;
use yii\base\Widget;
use yii\widgets\ActiveForm;

class EditableHtmlAreaWidget extends Widget
{
	public ActiveForm $form;
	public Model $model;
	public string $field;

    public function run()
    {
        return $this->render('editable-html-area', [
        	'form' => $this->form,
        	'model' => $this->model,
        	'field' => $this->field,
        ]);
    }
}