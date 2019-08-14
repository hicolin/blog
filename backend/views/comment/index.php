<?php
use yii\helpers\Url;
?>

<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="javascript:;">首页</a>
        <a href="javascript:;">评论管理</a>
        <a href="javascript:;">
          <cite>评论列表</cite>
        </a>
      </span>
    <a class="layui-btn layui-btn-small refresh-btn" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:38px">ဂ</i></a>
</div>
<div class="x-body">
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so">
            <input type="text" name="search[nickname]"  placeholder="昵称"
                   value="<?= isset($search['nickname']) ? $search['nickname'] : '' ?>" autocomplete="off" class="layui-input">
            <input type="text" name="search[tel]"  placeholder="QQ"
                   value="<?= isset($search['qq']) ? $search['qq'] : '' ?>" autocomplete="off" class="layui-input">
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
        <span class="x-right" style="line-height:40px">共有数据：<span class="count_num"><?= $pagination->totalCount ?></span> 条 ( <?= $pagination->getPageCount() ?> 页 )</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <th>
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>ID</th>
            <th>用户</th>
            <th>评论</th>
            <th>回复用户</th>
            <th>文章</th>
            <th>类型</th>
            <th>IP</th>
            <th>位置</th>
            <th>状态</th>
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
                <td><?= $list['member']['nickname'] ?><br><?= $list['member']['qq'] ?></td>
                <td>
                    <a href="javascript:;" style="color: rgb(1, 166, 255)" onclick="viewComment('<?= $list['id'] ?>', '<?= Url::to([$this->context->id . '/view-comment']) ?>')">查看</a>
                </td>
                <td><?= $list['user']['nickname'] ?><br><?= $list['user']['qq'] ?></td>
                <td>
                    <?php if ($list['type'] == 1): ?>
                    <a href="javascript:;" style="color: rgb(1, 166, 255)" onclick="x_admin_show('查看', '<?= $frontArticleUrl . $list['article']['id']?>')" ><?= $list['article']['title'] ?></a>
                    <?php else: ?>
                    <a href="javascript:;" style="color: rgb(1, 166, 255)" onclick="x_admin_show('查看', '<?= $frontMessageUrl ?>')" >留言板</a>
                    <?php endif; ?>
                </td>
                <td><?= $typeArr[$list['type']] ?></td>
                <td><?= $list['ip'] ?></td>
                <td><?= $list['location'] ?></td>
                <td class="td-status">
                    <span class="layui-form" onclick="changeStatus('<?= $list['id'] ?>', '<?= $list['status'] ?>')">
                        <input type="checkbox" name="status" lay-skin="switch" lay-text="显示|隐藏"
                            <?= $list['status'] == 1 ? 'checked' : '' ?>>
                    </span>
                </td>
                <td><?= date('Y-m-d H:i:s', $list['create_time']) ?></td>
                <td class="td-manage">
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

    function viewComment(id, url) {
        layer.load(3);
        $.post(url, {id: id}, function (res) {
            layer.closeAll();
            if (res.status === 200) {
                layer.open({
                    title: '查看评论',
                    type: 1,
                    area: ['90%', '90%'],
                    content: '<div style="padding: 20px">' + res.data + '</div>'
                });
            } else {
                layer.msg(res.msg, {icon: 2, time: 1500})
            }
        }, 'json');
    }
</script>
<?php $this->endBlock() ?>
