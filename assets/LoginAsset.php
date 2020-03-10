<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'Login_v1/fonts/font-awesome-4.7.0/css/font-awesome.min.css',
        'Login_v1/vendor/animate/animate.css',
        'Login_v1/vendor/css-hamburgers/hamburgers.min.css',
        'Login_v1/vendor/select2/select2.min.css',
        'Login_v1/css/util.css',
        'Login_v1/css/main.css',
    ];
    public $js = [
        'Login_v1/vendor/bootstrap/js/popper.js',
        'Login_v1/vendor/select2/select2.min.js',
        'Login_v1/vendor/tilt/tilt.jquery.min.js',
        'Login_v1/js/main.js'
    ];
    public $depends = [
        // 'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
