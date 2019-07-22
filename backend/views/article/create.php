<?php
use yii\helpers\Url;
?>

    <script src="<?= Url::to('@web/plugins/kindeditor/kindeditor-all.js') ?>"></script>
    <script src="<?= Url::to('@web/plugins/kindeditor/lang/zh-CN.js') ?>"></script>
    <style>
       .layui-form-item .layui-input-inline{width: 380px }
    </style>

<div class="x-body layui-anim layui-anim-up">
    <form class="layui-form">
        <div class="layui-form-item">
            <label for="title" class="layui-form-label">标题</label>
            <div class="layui-input-inline">
                <input type="text" name="title" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="keyword" class="layui-form-label">关键字</label>
            <div class="layui-input-inline">
                <input type="text" name="keyword" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label for="flag" class="layui-form-label">标识</label>
                <div class="layui-input-inline">
                    <select name="flag">
                        <?php foreach ($flagArr as $k => $v): ?>
                            <option value="<?= $k ?>"><?= $v ?></option>
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
                            <option value="<?= $k ?>"><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="radio" value="1" name="status" lay-skin="primary" title="显示" checked="">
                <input type="radio" value="2" name="status" lay-skin="warning" title="隐藏">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="content" class="layui-form-label">内容</label>
            <div class="layui-input-block">
                <textarea name="content" placeholder="请输入内容" style="width: 760px; height: 250px" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">
            </label>
            <button  class="layui-btn" lay-filter="add" lay-submit="">
                增加
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
        form.on('submit(add)', function(data){
            data.field.content = editor.html();
            $.post('<?= Url::to([$this->context->id . '/create']) ?>', data.field, function (res) {
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
</script>
<?php $this->endBlock() ?>
