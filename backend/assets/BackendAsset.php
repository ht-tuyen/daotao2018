<?php

namespace backend\assets;

use yii\web\AssetBundle;
use \yii\web\View;

/**
 * Main backend application asset bundle.
 */
class BackendAsset extends AssetBundle
{
    public $sourcePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['position' => View::POS_END];
    public $css = [
        'css/ionicons.min.css',
        'css/font-awesome.min.css',
    ];
    public $js = [
        'js/modal.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}
