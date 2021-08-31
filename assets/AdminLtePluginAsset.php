<?php
namespace app\assets;
use yii\web\AssetBundle;
class AdminLtePluginAsset extends AssetBundle
{
//    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins';
    public $css = [
        'chart.js/Chart.min.css',
        'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic',
        // more plugin CSS here
    ];
    public $js = [
        'chart.js/Chart.bundle.min.js'
        // more plugin Js here
    ];
    public $depends = [
//        'dmstr\adminlte\web\AdminLteAsset',
    ];
}