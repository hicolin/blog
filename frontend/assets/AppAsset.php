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
        'font-awesome/css/font-awesome.min.css',
        'css/animate.min.css',
        'layui/css/layui.css',
        'css/master.css',
        'css/gloable.css',
        'css/nprogress.css',
        'css/blog.css',
        'css/message.css',
    ];
    public $js = [
        'layui/layui.js',
        'js/wow.min.js',
        'js/global.js',
        'js/plugins/nprogress.js',
        'js/heart.js',
    ];
    public $depends = [
    ];
}
