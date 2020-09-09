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
class ConceptAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "concept/assets/vendor/fonts/circular-std/style.css",
        "concept/assets/vendor/bootstrap/css/bootstrap.min.css",
        "concept/assets/libs/css/style.css",
        "concept/assets/vendor/fonts/fontawesome/css/fontawesome-all.css",
        "concept/assets/vendor/charts/chartist-bundle/chartist.css",
        "concept/assets/vendor/charts/morris-bundle/morris.css",
        "concept/assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css",
        "concept/assets/vendor/charts/c3charts/c3.css",
        "concept/assets/vendor/fonts/flag-icon-css/flag-icon.min.css",
    ];
    public $js = [
        "concept/assets/vendor/bootstrap/js/bootstrap.bundle.js",
        "concept/assets/vendor/slimscroll/jquery.slimscroll.js",
        // "concept/assets/libs/js/main-js.js",
        // "concept/assets/vendor/charts/chartist-bundle/chartist.min.js",
        // "concept/assets/vendor/charts/sparkline/jquery.sparkline.js",
        // "concept/assets/vendor/charts/morris-bundle/raphael.min.js",
        // "concept/assets/vendor/charts/morris-bundle/morris.js",
        // "concept/assets/vendor/charts/c3charts/c3.min.js",
        // "concept/assets/vendor/charts/c3charts/d3-5.4.0.min.js",
        // "concept/assets/vendor/charts/c3charts/C3chartjs.js",
        // "concept/assets/libs/js/dashboard-ecommerce.js",
    ];
    public $depends = [
        // 'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset',
        // 'yii\bootstrap\BootstrapPluginAsset',
    ];
}
