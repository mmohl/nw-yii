<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets\concept;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ConceptLoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "concept/assets/vendor/bootstrap/css/bootstrap.min.css",
        "concept/assets/vendor/fonts/circular-std/style.css",
        "concept/assets/libs/css/style.css",
        "concept/assets/vendor/fonts/fontawesome/css/fontawesome-all.css"
    ];
    public $js = [
        "concept/assets/vendor/bootstrap/js/bootstrap.bundle.js"
    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset',
        // 'yii\bootstrap\BootstrapPluginAsset',
    ];
}
