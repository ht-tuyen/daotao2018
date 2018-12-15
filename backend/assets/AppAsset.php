<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte';
    public $js = [
        //'plugins/datatables/jquery.dataTables.js',
        //'plugins/datatables/dataTables.bootstrap.min.js',
        //'plugins/sparkline/jquery.sparkline.min.js',
        //'plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
        //'plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
        //'plugins/slimScroll/jquery.slimscroll.min.js',
        //'plugins/slimScroll/jquery.slimscroll.min.js',
        //'plugins/chartjs/Chart.min.js',
        //'plugins/morris/morris.min.js',
    ];
    public $css = [
        //'plugins/datatables/dataTables.bootstrap.css',
        'dist/css/AdminLTE.min.css',
        'dist/css/skins/_all-skins.min.css',
        //'plugins/jvectormap/jquery-jvectormap-1.2.2.css',
        //'plugins/morris/morris.css',
    ];
    public $depends = [
        'dmstr\web\AdminLteAsset',
    ];
}
