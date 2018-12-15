<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.css',
        'css/owl.carousel.css',
        'css/jquery.countdown.css',
        'css/loader.css',
        'css/docs.css',
        'css/site.css',
    ];
    public $js = [
        'js/jquery-1.12.4.min.js',
        'js/bootstrap.js',
        'js/owl.carousel.js',
        'js/jquery.form-validator.min.js',
        //'js/map-styleMain.js',
        'js/placeholder.js',
        'js/coustem.js',
        'js/countdown-script.js',
        'js/jquery.countdown.js',
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
