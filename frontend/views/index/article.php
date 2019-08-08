<?php
use yii\helpers\Url;
?>
<style>
    @media screen and (max-width: 1200px){
        .artiledetail img{width: 100%};
    }
</style>
<link rel="stylesheet" href="<?= Url::to('@web/js/plugins/highlight/highlight.min.css') ?>">
<script src="<?= Url::to('@web/js/plugins/highlight/highlight.min.js') ?>"></script>

<div class="doc-container" id="doc-container">
    <div class="container-fixed">
        <div class="col-content" style="width:100%">
            <div class="inner">
                <article class="article-list">
                    <section class="article-item">
                        <aside class="title" style="line-height:1.5;">
                            <h4><?= $article['title'] ?></h4>
                            <p class="fc-grey fs-14">
                                <small>
                                    作者：<a href="javascript:void(0)" target="_blank" class="fc-link">Colin</a>
                                </small>
                                <small class="ml10">围观群众：<i class="readcount"><?= $article['view_num'] ?></i></small>
                                <small class="ml10">更新于 <label><?= date('Y-m-d', $article['create_time']) ?></label> </small>
                            </p>
                        </aside>
                        <div class="time mt10" style="padding-bottom:0;">
                            <span class="day"><?= date('d', $article['create_time']) ?></span>
                            <span class="month fs-18"><?= date('m', $article['create_time']) ?><small class="fs-14">月</small></span>
                            <span class="year fs-18"><?= date('Y', $article['create_time']) ?></span>
                        </div>
                        <div class="content artiledetail" style="border-bottom: 1px solid #e1e2e0; padding-bottom: 20px;">
                            <?= htmlspecialchars_decode($article['content']) ?>
                            <div class="copyright mt20">
                                <p class="f-toe fc-black">
                                    非特殊说明，本文版权归 Colin 所有，转载请注明出处.
                                </p>
                                <p class="f-toe">
                                    本文标题：
                                    <a href="javascript:void(0)" class="r-title"><?= $article['title'] ?></a>
                                </p>
                                <p class="f-toe">
                                    本文网址：
                                    <a href="#"><?= Url::current([], true) ?></a>
                                </p>
                            </div>
                            <h6>延伸阅读</h6>
                            <ol class="b-relation">
                                <li class="f-toe">上一篇：<a href="<?= $relationArticle['prevArticle'] ? Url::to(['index/article', 'id' => $relationArticle['prevArticle']['id']]) : '#'?>"><?= $relationArticle['prevArticle']['title'] ? : '没有了' ?></a></li>
                                <li class="f-toe">下一篇：<a href="<?= $relationArticle['nextArticle'] ? Url::to(['index/article', 'id' => $relationArticle['nextArticle']['id']]) : '#'?>"><?= $relationArticle['nextArticle']['title'] ? : '没有了' ?></a></li>
                            </ol>
                        </div>
                        <div class="f-cb"></div>
                        <div class="mt20 f-fwn fs-24 fc-grey comment" style="padding-top: 20px;">
                        </div>
                        <fieldset class="layui-elem-field layui-field-title">
                            <legend>发表评论</legend>
                            <div class="layui-field-box">
                                <div class="leavemessage" style="text-align:initial">
                                    <form class="layui-form blog-editor" onsubmit="return false">
                                        <div class="layui-form-item">
                                            <textarea name="editorContent" id="remarkEditor" placeholder="请输入内容" class="layui-textarea layui-hide"></textarea>
                                        </div>
                                        <blockquote class="layui-elem-quote" style="color: #999">提交留言，需要输入QQ号，用来快速获取您的头像和昵称。</blockquote>
                                        <div class="layui-form-item">
                                            <button class="layui-btn" id="sub_btn">提交留言</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </fieldset>
                    </section>
                    <div class="mt20">
                        <ul class="message-list" id="message-list">
                        </ul>
                    </div>
                </article>
            </div>
        </div>
    </div>
</div>

<?php $this->beginBlock('footer') ?>
<script src="<?= Url::to('@web/js/comment.js') ?>"></script>
<script>
    hljs.initHighlighting();

    var article_id = '<?= $id ?>';
    layui.use(['layedit', 'jquery', 'layer', 'flow'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var layedit = layui.layedit;
        var flow = layui.flow;

        var editIndex = layedit.build('remarkEditor', {
            height: 150,
            tool: ['face', '|', 'link'],
        });

        $('#sub_btn').click(function () {
            var content = $.trim(layedit.getContent(editIndex));
            if (!content) {
                return layer.msg('留言内容不能为空');
            }
            layer.prompt({title: '请输入您的QQ号', formType: 3, success: function () {
                    setLocalQQ('.layui-layer-input', $);
                }}, function(text, index){
                var loadIndex = layer.load(3);
                var data = {qq: text, content: content, article_id: article_id, type: 1};
                $.post('<?= Url::to(['index/add-message']) ?>', data, function (res) {
                    if (res.status === 200) {
                        layer.closeAll();
                        layer.msg(res.msg, function () {
                            var list = [];
                            layui.each(res.data, function (index, item) {
                                var html = '';
                                html = jointSingleHtml(html, item);
                                list.push(html);
                            });
                            $('#message-list').prepend(list.join(''));
                            layedit.setContent(editIndex, '');
                            saveLocalQQ(text);
                        });
                    } else {
                        layer.close(loadIndex);
                        layer.msg(res.msg)
                    }
                }, 'json')
            });
        });

        flow.load({
            elem: '#message-list',
            isLazyimg: true,
            done: function (page, next) {
                var list = [];
                $.get('<?= Url::to(['index/article'])?>', {page: page, article_id: article_id}, function (res) {
                    res = res.data;
                    layui.each(res.data, function (index, item) {
                        var html = '';
                        html = jointSingleHtml(html, item);
                        list.push(html);
                    });
                    next(list.join(''), page < res.pages)
                }, 'json')
            }
        });

        //回复按钮点击事件
        $('#message-list').on('click', '.btn-reply', function () {
            var targetId = $(this).data('id')
                ,targetName = $(this).data('nickname')
                , targetPid = $(this).data('pid')
                , $container = $(this).parent('p').parent().siblings('.replycontainer');
            if ($(this).text() == '回复') {
                $('.replycontainer').addClass('layui-hide');
                $('.btn-reply').text('回复');
                $container.find('textarea').attr('placeholder', '回复【' + targetName + '】');
                $container.removeClass('layui-hide');
                $(this).parents('.message-list li').find('.btn-reply').text('回复');
                $(this).text('收起');
            } else {
                $container.addClass('layui-hide');
                $(this).text('回复');
            }

            // 提交回复
            $container.find('button').click(function () {
                var content = $.trim($container.find('textarea').val());
                if (!content) {
                    return layer.msg('回复内容不能为空');
                }
                layer.prompt({title: '请输入您的QQ号', formType: 3, success: function () {
                        setLocalQQ('.layui-layer-input', $);
                    }}, function(text, index){
                    var loadIndex = layer.load(3);
                    var data = {qq: text, content: content, pid: targetPid, article_id: article_id,
                        to_user_id: targetId, type: 1};
                    $.post('<?= Url::to(['index/add-message']) ?>', data, function (res) {
                        if (res.status === 200) {
                            layer.closeAll();
                            layer.msg(res.msg, function () {
                                var list = [];
                                layui.each(res.data, function (index, item) {
                                    var html = '';
                                    html = jointSecondHtml(html, item, targetPid);
                                    list.push(html);
                                });
                                $container.before(list.join(''));
                                $container.find('textarea').val('');
                                $('.btn-reply').text('回复');
                                $('.replycontainer').addClass('layui-hide');
                                setLocalQQ('.layui-layer-input', $);
                            });
                        } else {
                            layer.close(loadIndex);
                            layer.msg(res.msg)
                        }
                    }, 'json')
                });
            })
        });
    });

</script>
<?php $this->endBlock() ?>
