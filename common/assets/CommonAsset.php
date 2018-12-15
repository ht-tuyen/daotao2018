<?php
namespace common\assets;
use yii\web\AssetBundle;
/**
 * Main frontend application asset bundle.
 */
class CommonAsset extends AssetBundle
{
    public $sourcePath = '@common/assets/scripts';
    public $css = [
        'css/toastr.min.css',
        'css/quill.snow.css',
        'css/quill.core.css',
        'css/quill.bubble.css'
    ];
    public $js = [
        'js/jquery.min.js',
        'js/axios.min.js',
        'js/quill.js',
        'js/toastr.min.js',
        'js/vue.js',
        'js/vue-quill-editor.js',
    ];
    public $depends = [
    ];
}