<?php

namespace backend\assets;

use yii\web\AssetBundle;
use \yii\web\View;

/**
 * Main backend application asset bundle.
 */
class CustomAsset extends AssetBundle
{
    public $sourcePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['position' => View::POS_END];
    public $css = [
        'css/ionicons.min.css',
        'css/font-awesome.min.css',
        'css/site.css',
        //'css/jquery.loading-indicator.css',
    ];
    public $js = [
        //'js/raphael-min.js',
        'js/custom.js',
        'js/custom-ckeditor.js',
        'js/ckeditor5.js',
        //'js/formatCurrency/jquery.formatCurrency.js',
        //'js/handlebars-v4.0.11.js',        
        //'js/modal.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
        'backend\assets\AppAsset',
    ];
}
