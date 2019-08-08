<?php
use yii\helpers\Url;
?>

<div class="doc-container" id="doc-container">
    <div class="container-fixed">
        <div class="col-content">
            <div class="inner">
                <article class="article-list bloglist" id="LAY_bloglist" >
                </article>
            </div>
        </div>
        <div class="col-other">
            <div class="inner">
                <div class="other-item wow swing" id="categoryandsearch">
                    <div class="search">
                        <label class="search-wrap">
                            <input type="text" name="keyword" placeholder="输入关键字搜索" />
                            <span class="search-icon">
					                <i class="fa fa-search"></i>
					            </span>
                        </label>
                    </div>
                    <ul class="category mt20" id="category">
                        <li data-index="0" class="slider"></li>
                        <li data-index="1"><a href="<?= Url::to('/') ?>">全部文章<span class="article-count" style="color: #2ea7e0"><?= $articleNum['all'] ?></span></a></li>
                        <li data-index="2"><a href="<?= Url::to(['index/index', 'type' => 1]) ?>">后端<span class="article-count"><?= $articleNum['backend'] ?></span></a></li>
                        <li data-index="3"><a href="<?= Url::to(['index/index', 'type' => 2]) ?>">前端<span class="article-count"><?= $articleNum['frontend'] ?></span></a></li>
                        <li data-index="4"><a href="<?= Url::to(['index/index', 'type' => 3]) ?>">运维<span class="article-count"><?= $articleNum['linux'] ?></span></a></li>
                        <li data-index="5"><a href="<?= Url::to(['index/index', 'type' => 4]) ?>">杂项<span class="article-count"><?= $articleNum['other'] ?></span></a></li>
                    </ul>
                </div>
                <!--遮罩-->
                <div class="blog-mask animated layui-hide" style="visibility: hidden"></div>
                <div class="other-item wow swing">
                    <h5 class="other-item-title">热门文章</h5>
                    <div class="inner">
                        <ul class="hot-list-article">
                            <?php foreach ($hotArticles as $list): ?>
                                <li><a href="<?= Url::to(['index/article', 'id' => $list['id']]) ?>"><?= $list['title'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="other-item wow swing">
                    <h5 class="other-item-title">最新留言访客</h5>
                    <div class="inner">
                        <dl class="vistor">
                            <?php foreach ($guests as $list): ?>
                                <dd><a href="javascript:;"><img src="<?= $list['member']['avatar'] ?>"><cite><?= $list['member']['nickname'] ?></cite></a></dd>
                            <?php endforeach; ?>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->beginBlock('footer') ?>
<script>
    var type = '<?= $type ?>';
    var keyword = '<?= $keyword ?>';
    if (!type) type = 0;
    layui.use('flow', function () {
        var $ = layui.jquery;
        var flow = layui.flow;
        flow.load({
            elem: '#LAY_bloglist',
            isLazyimg: true,
            done: function (page, next) {
                var list = [];
                $.get('<?= Url::to(['index/index'])?>', {type: type, page: page, keyword: keyword}, function (res) {
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

        $('.fa-search').click(function () {
            var keyword = $.trim($('input[name="keyword"]').val());
            if (!keyword) {
                return layer.msg('关键词不能为空');
            }
            location.href = '<?= Url::to('/') ?>' + '?keyword=' + keyword;
        })

    });

    function jointSingleHtml(html, item) {
        html = `
            <section class="article-item zoomIn article">
                        <div class="fc-flag">${item.type}</div>
                        <h5 class="title">
                            <span class="fc-blue">【${item.flag}】</span>
                            <a href="${item.url}" target="_blank">${item.title}</a>
                        </h5>
                        <div class="time">
                            <span class="day">${item.day}</span>
                            <span class="month fs-18">${item.month}<span class="fs-14">月</span></span>
                            <span class="year fs-18 ml10">${item.year}</span>
                        </div>
                        <div class="content">
                            <a href="${item.url}" class="cover img-light" target="_blank">
                                <img lay-src="${item.pic}">
                            </a>
                            ${item.summary}
                        </div>
                        <div class="read-more" style="clear: both">
                            <a href="${item.url}" class="fc-black f-fwb" target="_blank">继续阅读</a>
                        </div>
                        <aside class="f-oh footer">
                            <div class="f-fl tags">
                                <span class="fa fa-tags fs-16"></span>
                                <a class="tag">${item.keyword}</a>
                            </div>
                            <div class="f-fr">
									<span class="read">
										<i class="fa fa-eye fs-16"></i>
										<i class="num">${item.view_num}</i>
									</span>
                                <span class="ml20">
										<i class="fa fa-comments fs-16"></i>
										<a href="javascript:void(0)" class="num fc-grey">${item.comment_num}</a>
									</span>
                            </div>
                        </aside>
                    </section>
        `;
        return html;
    }
</script>
<?php $this->endBlock() ?>
