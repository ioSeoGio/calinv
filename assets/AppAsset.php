<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use Yii;
use yii\helpers\Json;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/dark-theme.css',
        'css/site.css',
        'css/alert-messages.css',
        'css/reversed-financial-report.css',

        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css',
    ];

    public $js = [
        'js/alert-messages.js',
        'js/fix-kartik-masked-input.js',

        'js/jquery-cookie.js',
        'js/theme-switcher.js',
    ];
    public $jsOptions = ['position' => View::POS_HEAD];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];

    public function init()
    {
        parent::init();

        $options = [
        ];
        Yii::$app->view->registerJs(
            "var yiiGlobal = " . Json::htmlEncode($options).";",
            View::POS_HEAD,
            'yiiGlobal'
        );

        $images = [
        ];
        Yii::$app->view->registerJs(
            "var yiiImages = " . Json::htmlEncode($images).";",
            View::POS_HEAD,
            'yiiImages'
        );
    }
}
