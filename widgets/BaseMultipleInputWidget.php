<?php
namespace app\widgets;

use Yii;
use yii\bootstrap5\Widget;


class BaseMultipleInputWidget extends Widget
{
    public $form;
    public $model;
    public $fieldName;

    public $optionsDefault = [
        'class' => 'form-control',
    ];

    public function run()
    {
        return $this->render('base-multiple-field', [
            'form' => $this->form,
            'model' => $this->model,
            'fieldName' => $this->fieldName,
            'options' => array_merge($this->optionsDefault, $this->options),
        ]);
    }
}
