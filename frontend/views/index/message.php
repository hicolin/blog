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
                <form class="layui-form blog-editor" action="">
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
                $.post('<?= Url::to(['index/add-message']) ?>', {qq: text, content: content}, function (res) {
                    if (res.status === 200) {
                        layer.closeAll();
                        layer.msg(res.msg, function () {
                           // todo dom操作
                            layedit.setContent(editIndex, '', false);
                        });
                        console.log(res)
                    } else {
                        layer.close(loadIndex);
                        layer.msg(res.msg)
                    }
                }, 'json')
            });
        })

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
        })
    })

    function jointSingleHtml(html, item) {
        console.log(item);
        html = `
            <li class="zoomIn article">
                    <div class="comment-parent">
                        <img lay-src="${item.member.avatar}" />
                        <div class="info">
                            <span class="username">${item.member.nickname}
                            ${(function () {
                               if (item.member.qq == 811687790) {
                                   return `<span class="layui-badge">博主</span>`;
                               } else {
                                   return '';
                               }
                            })()}
                        </span>
                        </div>
                        <div class="comment-content">
                            ${item.content}
                        </div>
                        <p class="info info-footer">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <span>${item.location}</span>
                            <span class="comment-time">${item.create_time}</span>
                            <a href="javascript:;" class="btn-reply" data-targetid="1" data-targetname="燕十三">回复</a>
                        </p>
                    </div>
                    `;
        if (item.child.length > 0) {
            item.child.forEach(function (val) {
                html += `
                    <hr />
                    <div class="comment-child">
                        <img lay-src="${val.member.avatar}">
                        <div class="info">
                            <span class="username">${val.member.nickname}
                                ${(function () {
                                    if (val.member.qq == 811687790) {
                                        return `<span class="layui-badge">博主</span>`;
                                    } else {
                                        return '';
                                    }
                                })()}
                            </span>
                            <span style="padding-right:0;margin-left:-5px;">回复</span>
                            <span class="username">${val.user.nickname}
                                ${(function () {
                                    if (val.user.qq == 811687790) {
                                        return `<span class="layui-badge">博主</span>`;
                                    } else {
                                        return '';
                                    }
                                })()}
                            </span>
                            <div>${val.content}</div>
                        </div>
                        <p class="info">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <span>${val.location}</span>
                            <span class="comment-time">${val.create_time}</span>
                            <a href="javascript:;" class="btn-reply" data-targetid="2" data-targetname="燕十四">回复</a>
                        </p>
                    </div>
                    `;
            })
        }
        html += `
                    <div class="replycontainer layui-hide">
                        <form class="layui-form" action="">
                            <input type="hidden" name="remarkId" value="1">
                            <input type="hidden" name="targetUserId" value="0">
                            <div class="layui-form-item">
                                <textarea name="replyContent" lay-verify="replyContent" placeholder="请输入回复内容" class="layui-textarea" style="min-height:80px;"></textarea>
                            </div>
                            <div class="layui-form-item">
                                <button class="layui-btn layui-btn-xs" lay-submit="formReply" lay-filter="formReply">提交</button>
                            </div>
                        </form>
                    </div>
                </li>
        `;
        return html;
    }
</script>
<?php $this->endBlock() ?>
