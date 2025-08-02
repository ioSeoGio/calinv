<?php
use yii\helpers\Url;

/** @var \yii\base\Model $form */
/** @var \yii\db\ActiveRecord $model */
/** @var string $field */

?>

<?= $form->field($model, $field)->widget(\vova07\imperavi\Widget::class, [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 100,

//        'imageUpload' => Url::to(['/admin/widget-image/image-upload']),
//        'imageDelete' => Url::to(['/admin/widget-image/image-delete']),
//        'imageManagerJson' => Url::to(['/admin/widget-image/images-get']),

//        'fileUpload' => Url::to(['/admin/widget-image/file-upload']),
//        'fileManagerJson' => Url::to(['/admin/widget-image/files-get']),

        'buttonSource' => true,
        'convertDivs' => false,
        'replaceDivs' => false,
        'replaceTags' => false,

        'removeEmptyTags' => false,
        'paragraphize' => false,
        'pastePlainText'  => true,
        'toolbarFixedBox' => true,

        'plugins' => [
            'clips',
            'fullscreen',
//            'filemanager',
//            'imagemanager',
        ],
        'clips' => [
            [''],
        ],
    ],
])->label(false);?>