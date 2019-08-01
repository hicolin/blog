<?php
use yii\helpers\Url;
?>
<style>
    @media screen and (max-width: 500px){
        .artiledetail img{width: 100%};
    }
</style>

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
                        </div>
                        <div class="f-cb"></div>
                        <div class="mt20 f-fwn fs-24 fc-grey comment" style="border-top: 1px solid #e1e2e0; padding-top: 20px;">
                        </div>
                        <fieldset class="layui-elem-field layui-field-title">
                            <legend>发表评论</legend>
                            <div class="layui-field-box">
                                <div class="leavemessage" style="text-align:initial">
                                    <form class="layui-form blog-editor" action="">
                                        <input type="hidden" name="articleid" id="articleid" value="@Model.ID">
                                        <div class="layui-form-item">
                                            <textarea name="editorContent" lay-verify="content" id="remarkEditor" placeholder="请输入内容" class="layui-textarea layui-hide"></textarea>
                                        </div>
                                        <div class="layui-form-item">
                                            <button class="layui-btn" lay-submit="formLeaveMessage" lay-filter="formLeaveMessage">提交留言</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </fieldset>
                        <ul class="blog-comment" id="blog-comment"></ul>
                    </section>
                </article>
            </div>
        </div>
    </div>
</div>
