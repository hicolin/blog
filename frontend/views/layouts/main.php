<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width" />
    <meta name="author" content="colin" />
    <meta name="robots" content="all" />
    <title><?= $this->title ?> - Colin 博客</title>
    <link rel="shortcut icon" href="<?= Url::to('@web/favicon.ico') ?>" type="image/x-icon" />
    <?php $this->head() ?>

    <?php if (isset($this->blocks['header'])): ?>
        <?= $this->blocks['header']; ?>
    <?php endif; ?>

    <!--百度统计-->
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?d6dbaf3b45a98c6a0d3ce39ab6325dda";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>

</head>

<body>
<?php $this->beginBody() ?>
<div class="header"></div>
<header class="gird-header">
    <div class="header-fixed">
        <div class="header-inner">
            <a href="<?= Url::to('/') ?>" class="header-logo" id="logo">Mr.Colin</a>
            <nav class="nav" id="nav">
                <ul>
                    <li><a href="<?= Url::to('/') ?>">首页</a></li>
                    <li><a href="<?= Url::to(['index/index', 'type' => 1]) ?>">后端</a></li>
                    <li><a href="<?= Url::to(['index/index', 'type' => 2]) ?>">前端</a></li>
                    <li><a href="<?= Url::to(['index/index', 'type' => 3]) ?>">运维</a></li>
                    <li><a href="<?= Url::to(['index/index', 'type' => 4]) ?>">杂项</a></li>
                    <li><a href="<?= Url::to(['index/message']) ?>">留言</a></li>
                    <li><a href="<?= Url::to(['index/about']) ?>">关于</a></li>
                </ul>
            </nav>
            <a class="phone-menu">
                <i></i>
                <i></i>
                <i></i>
            </a>
        </div>
    </div>
</header>

<?= $content ?>

<footer class="grid-footer">
    <div class="footer-fixed">
        <div class="copyright">
            <div class="info">
                <div class="contact">
                    <a href="https://gitee.com/colin_2048" class="github" target="_blank"><i class="fa fa-github"></i></a>
                    <a href="https://wpa.qq.com/msgrd?v=3&uin=811687790&site=qq&menu=yes" class="qq" target="_blank" title="811687790"><i class="fa fa-qq"></i></a>
                    <a href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&email=XzwwMzYxAG5vbWsfLi5xPDAy" class="email" target="_blank" title="811687790@qq.com"><i class="fa fa-envelope"></i></a>
                    <a href="<?= Url::to(['index/about']) ?>" class="weixin"><i class="fa fa-weixin"></i></a>
                </div>
                <p class="mt05">
                    Copyright &copy; 2019-<?= date('Y') ?> Colin All Rights Reserved
                </p>
            </div>
        </div>
    </div>
</footer>
<?php $this->endBody() ?>

<script>
    NProgress.start();
    window.onload = function () {
        NProgress.done();
    };
    new WOW().init();
</script>

<?php if (isset($this->blocks['footer'])): ?>
    <?= $this->blocks['footer']; ?>
<?php endif; ?>

</body>
</html>
<?php $this->endPage() ?>
