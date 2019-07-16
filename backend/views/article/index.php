<?php
use yii\helpers\Url;
?>

<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="javascript:;">首页</a>
        <a href="javascript:;">文章管理</a>
        <a href="javascript:;">
          <cite>文章列表</cite>
        </a>
      </span>
    <a class="layui-btn layui-btn-small refresh-btn" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:38px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so">
            <input type="text" name="search[title]"  placeholder="标题"
                   value="<?= isset($search['title']) ? $search['title'] : '' ?>" autocomplete="off" class="layui-input">
            <div class="layui-inline layui-show-xs-block">
                <select name="search[type]">
                    <option value="">类型</option>
                    <?php foreach ($typeArr as $k => $v): ?>
                    <option value="<?= $k ?>" <?= $search['type'] == $k ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="layui-inline layui-show-xs-block">
                <select name="search[status]">
                    <option value="">状态</option>
                    <?php foreach ($statusArr as $k => $v): ?>
                        <option value="<?= $k ?>" <?= $search['status'] == $k ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input class="layui-input" placeholder="开始日" name="search[b_time]" id="start"
                   value="<?= isset($search['b_time']) ? $search['b_time'] : '' ?>" autocomplete="off">
            <input class="layui-input" placeholder="截止日" name="search[e_time]" id="end"
                   value="<?= isset($search['e_time']) ? $search['e_time'] : '' ?>" autocomplete="off">
            <button class="layui-btn" type="submit"><i class="layui-icon">&#xe615;</i></button>
            <button class="layui-btn" type="button" onclick="location.href= '<?= Url::to([$this->context->id . '/index'])?>'" >
                <i class="layui-icon">&#xe666;</i>
            </button>
        </form>
    </div>
    <xblock>
        <button class="layui-btn layui-btn-danger" onclick="batch_del('<?= Url::to([$this->context->id . '/batch-del']) ?>')"><i class="layui-icon"></i>批量删除</button>
        <button class="layui-btn" onclick="x_admin_show('添加文章','<?=  Url::to([$this->context->id . '/create']) ?>')"><i class="layui-icon"></i>添加</button>
        <span class="x-right" style="line-height:40px">共有数据：<span class="count_num"><?= $pagination->totalCount ?></span> 条 ( <?= $pagination->getPageCount() ?> 页 )</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th>
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>ID</th>
            <th>标题</th>
            <th>内容</th>
            <th>标识</th>
            <th>类型</th>
            <th>关键字</th>
            <th>状态</th>
            <th>浏览量</th>
            <th>评论量</th>
            <th>创建时间</th>
            <th>操作</th>
        </thead>
        <tbody>
        <?php foreach ($models as $list): ?>
            <tr>
                <td>
                    <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?= $list['id']?>'><i class="layui-icon">&#xe605;</i></div>
                </td>
                <td><?= $list['id'] ?></td>
                <td><?= $list['title'] ?></td>
                <td style="color: dodgerblue; cursor: pointer">查看</td>
                <td><?= $flagArr[$list['flag']] ?></td>
                <td><?= $typeArr[$list['type']] ?></td>
                <td><?= $list['keyword'] ?></td>
                <td><?= $statusArr[$list['status']] ?></td>
                <td><?= $list['view_num'] ?></td>
                <td><?= $list['comment_num'] ?></td>
                <td><?= date('Y-m-d H:i:s', $list['create_time']) ?></td>
                <td class="td-manage">
                    <a title="编辑"  onclick="x_admin_show('编辑','<?= Url::to([$this->context->id . '/update', 'id' => $list['id']])?>')" href="javascript:;">
                        <i class="layui-icon">&#xe642;</i>
                    </a>
                    <a title="删除" onclick="del(this,'<?= $list['id'] ?>', '<?= Url::to([$this->context->id . '/del']) ?>')" href="javascript:;">
                        <i class="layui-icon">&#xe640;</i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($models)): ?>
        <p class="text-empty">-- 暂无数据 --</p>
    <?php endif; ?>

    <?= $this->render('@app/views/layouts/pagination', compact('pagination')) ?>

</div>

<?php $this->beginBlock('footer') ?>
<script>
    function changeStatus(id, status) {
        layer.load(3);
        $.post('<?= Url::to([$this->context->id . '/change-status']) ?>', {id: id, status: status}, function (res) {
            layer.closeAll();
            var icon = 2;
            if (res.status === 200){
                // $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                icon = 1;
            }
            layer.msg(res.msg, {icon: icon, time: 1500})
        }, 'json')
    }
</script>
<?php $this->endBlock() ?>
