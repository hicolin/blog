<?php
use yii\helpers\Url;
?>

<div class="doc-container" id="doc-container">
    <div class="container-fixed">
        <div class="container-inner wow flipInX">
            <section class="msg-remark">
                <h1>留言板</h1>
                <p>
                    沟通交流，拉近你我！
                </p>
            </section>
            <div class="textarea-wrap message" id="textarea-wrap">
                <form class="layui-form blog-editor" onsubmit="return false">
                    <div class="layui-form-item">
                        <textarea name="editorContent" id="remarkEditor" placeholder="请输入内容" class="layui-textarea layui-hide"></textarea>
                    </div>
                    <blockquote class="layui-elem-quote" style="color: #999">提交留言，需要输入QQ号，用来快速获取您的头像和昵称。</blockquote>
                    <div class="layui-form-item">
                        <button class="layui-btn" id="sub_btn" type="button">提交留言</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="f-cb"></div>
        <div class="mt20">
            <ul class="message-list" id="message-list">
            </ul>
        </div>
    </div>
</div>

<?php $this->beginBlock('footer') ?>
<script src="<?= Url::to('@web/js/comment.js') ?>"></script>
<script>
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
            layer.prompt({title: '请输入您的QQ号', formType: 3}, function(text, index){
                var loadIndex = layer.load(3);
                $.post('<?= Url::to(['index/add-message']) ?>', {qq: text, content: content, type: 2}, function (res) {
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
                $.get('<?= Url::to(['index/message'])?>', {page: page}, function (res) {
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
                layer.prompt({title: '请输入您的QQ号', formType: 3}, function(text, index){
                    var loadIndex = layer.load(3);
                    var data = {qq: text, content: content, pid: targetPid, article_id: 0, to_user_id: targetId, type: 2};
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
