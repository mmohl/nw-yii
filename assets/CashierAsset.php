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
class CashierAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'libraries/datatables/media/css/jquery.dataTables.min.css',
        'libraries/datatables/media/css/dataTables.bootstrap.min.css',
    ];
    public $js = [
        'libraries/datatables/media/js/jquery.dataTables.min.js',
        'libraries/datatables/media/js/dataTables.bootstrap.min.js',
        'libraries/lodash/dist/lodash.min.js',
        'scripts/cashier.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        // 'yii\web\YiiAsset'
    ];
}
