<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css',
        'css/chosen.min.css',
        'toast/toastr.css',
        'css/site.css',
    ];
    public $js = [
        'https://code.jquery.com/ui/1.12.1/jquery-ui.js',
        'js/chosen.jquery.min.js',
        'toast/toastr.js',
        'js/site.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}
