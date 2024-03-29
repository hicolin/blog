<?php
use yii\helpers\Url;
?>

<script src="<?= Url::to('@web/plugins/kindeditor/kindeditor-all.js?201908140956') ?>"></script>
<script src="<?= Url::to('@web/plugins/kindeditor/lang/zh-CN.js') ?>"></script>
<style>
    .layui-form-item .layui-input-inline{width: 380px }
</style>

<div class="x-body layui-anim layui-anim-up">
    <form class="layui-form">
        <div class="layui-form-item">
            <label for="title" class="layui-form-label">标题</label>
            <div class="layui-input-inline">
                <input type="hidden" name="id" value="<?= $model['id'] ?>">
                <input type="text" name="title" autocomplete="off" class="layui-input" value="<?= $model['title'] ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="keyword" class="layui-form-label">关键字</label>
            <div class="layui-input-inline">
                <input type="text" name="keyword" autocomplete="off" class="layui-input" value="<?= $model['keyword'] ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label for="flag" class="layui-form-label">标识</label>
                <div class="layui-input-inline">
                    <select name="flag">
                        <?php foreach ($flagArr as $k => $v): ?>
                            <option value="<?= $k ?>" <?= $model['flag'] == $k ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label for="type" class="layui-form-label">类型</label>
                <div class="layui-input-inline">
                    <select name="type">
                        <?php foreach ($typeArr as $k => $v): ?>
                            <option value="<?= $k ?>" <?= $model['type'] == $k ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="thumb" class="layui-form-label">头像</label>
            <div class="layui-input-inline">
                <?php if ($model['thumb']): ?>
                    <img src="<?= Url::to('@web' . '/' . $model['thumb']) ?>" class="thumb" style="width: 100px;height: 100px;cursor: pointer">
                <?php else: ?>
                    <img src="<?= Url::to('@web/images/add_img.png') ?>" class="thumb" style="width: 100px;height: 100px;cursor: pointer">
                <?php endif; ?>
                <input type="file" name="file" style="display: none" onchange="viewImg(this)">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="radio" value="1" name="status" lay-skin="primary" title="显示" <?= $model['status'] == 1 ? 'checked' : '' ?>>
                <input type="radio" value="2" name="status" lay-skin="warning" title="隐藏" <?= $model['status'] == 2 ? 'checked' : '' ?>>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="content" class="layui-form-label">内容</label>
            <div class="layui-input-block">
                <textarea name="content" placeholder="请输入内容" style="width: 760px; height: 250px" class="layui-textarea"><?= $model['content'] ?></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">
            </label>
            <button  class="layui-btn" lay-filter="update" lay-submit="">
                更新
            </button>
        </div>
    </form>
</div>

<?php $this->beginBlock('footer') ?>
<script>
    KindEditor.ready(function (K) {
        window.editor = K.create('textarea[name="content"]');
    });

    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form ,layer = layui.layer;

        //监听提交
        form.on('submit(update)', function(data){
            data.field.content = editor.html();
            data.field.thumb = $('.thumb').attr('src');
            layer.load(3);
            $.post('<?= Url::to([$this->context->id . '/update']) ?>', data.field, function (res) {
                layer.closeAll();
                if (res.status === 200) {
                    layer.msg(res.msg, {icon: 1,time: 1500}, function () {
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                        parent.location.reload();
                    })
                } else {
                    layer.msg(res.msg, {icon: 2, time: 1500})
                }
            }, 'json');
            return false;
        });
    });

    $('.thumb').click(function () {
        $('input[name="file"]').click();
    });

    // 图片预览
    function viewImg(obj) {
        var reads = new FileReader();
        f = obj.files[0];
        reads.readAsDataURL(f);
        reads.onload = function (e) {
            $(obj).parent().find('img').attr('src', this.result);
        }
    }
</script>
<?php $this->endBlock() ?>
