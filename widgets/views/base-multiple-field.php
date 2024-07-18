<?php
/** @var \yii\bootstrap5\ActiveForm $form */
/** @var \yii\base\Model $model */
/** @var string $fieldName */
/** @var array $options */

use unclead\multipleinput\MultipleInput;

?>

<?= $form->field($model, $fieldName, [
    'template' => '<div class="input-group mb-3">{input}<div class="input-group-append"></div>{error}{hint}</div>',
])
->input('number', $options)
->widget(MultipleInput::class, [
    'form' => $form,
    'iconMap' => [
        MultipleInput::ICONS_SOURCE_GLYPHICONS => [
            'add' => 'bi bi-plus',
            'drag-handle' => 'glyphicon glyphicon-menu-hamburger',
            'remove' => 'bi bi-telephone-plus-fill',
            'clone' => 'glyphicon glyphicon-duplicate',
        ],
    ],
    'max' => 10,
    'min' => 2,

    'enableError' => true,
    'showGeneralError' => true,

    'allowEmptyList' => false,
    'enableGuessTitle' => false,
    'addButtonPosition' => MultipleInput::POS_ROW,
    'addButtonOptions' => null,
    'removeButtonOptions' => [
        'class' => 'd-none',
    ],
])
->label(false) ?>
