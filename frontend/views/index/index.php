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
                            <input type="text" id="searchtxt" placeholder="输入关键字搜索" />
                            <span class="search-icon">
					                <i class="fa fa-search"></i>
					            </span>
                        </label>
                        <ul class="search-result"></ul>
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
                            <li> <a href="/Blog/Read/9">2018最新版QQ音乐api调用</a></li>
                            <li> <a href="/Blog/Read/12">模板分享</a></li>
                            <li> <a href="/Blog/Read/13">逆水寒</a></li>
                            <li> <a href="/Blog/Read/4">序章</a></li>
                            <li> <a href="/Blog/Read/7">解决百度分享插件不支持https</a></li>
                            <li> <a href="/Blog/Read/11">使用码云和VS托管本地代码</a></li>
                            <li> <a href="/Blog/Read/14">MUI框架-快速开发APP</a></li>
                            <li> <a href="/Blog/Read/8">NPOI导入导出Excel</a></li>
                        </ul>
                    </div>
                </div>
                <div class="other-item wow swing">
                    <h5 class="other-item-title">最新留言访客</h5>
                    <div class="inner">
                        <dl class="vistor">
                            <dd><a href="javasript:;"><img src="https://thirdqq.qlogo.cn/qqapp/101465933/72388EA977643E8F97111222675720B1/100"><cite>Anonymous</cite></a></dd>
                            <dd><a href="javasript:;"><img src="https://thirdqq.qlogo.cn/qqapp/101465933/342F777E313DDF5CCD6E3E707BB0770B/100"><cite>Dekstra</cite></a></dd>
                            <dd><a href="javasript:;"><img src="https://thirdqq.qlogo.cn/qqapp/101465933/EA5D00A72C0C43ECD8FC481BD274DEEC/100"><cite>惜i</cite></a></dd>
                            <dd><a href="javasript:;"><img src="https://thirdqq.qlogo.cn/qqapp/101465933/EF18CEC98150D2442183AA30F05AAD7B/100"><cite>↙Aㄨ计划 ◆莪↘</cite></a></dd>
                            <dd><a href="javasript:;"><img src="https://thirdqq.qlogo.cn/qqapp/101465933/3D8D91AD2BAFD36F5AC494DA51E270E6/100"><cite>.</cite></a></dd>
                            <dd><a href="javasript:;"><img src="https://thirdqq.qlogo.cn/qqapp/101465933/B745A110DAB712A0E6C5D0B633E905D3/100"><cite>Lambert.</cite></a></dd>
                            <dd><a href="javasript:;"><img src="https://thirdqq.qlogo.cn/qqapp/101465933/E9BA3A2499EC068B7917B9EF45C4D13C/100"><cite>64ღ</cite></a></dd>
                            <dd><a href="javasript:;"><img src="https://thirdqq.qlogo.cn/qqapp/101465933/09F92966169272DD7DD9999E709A0204/100"><cite>doBoor</cite></a></dd>
                            <dd><a href="javasript:;"><img src="https://thirdqq.qlogo.cn/qqapp/101465933/59991D53192643A1A651383847332EB6/100"><cite>毛毛小妖</cite></a></dd>
                            <dd><a href="javasript:;"><img src="https://thirdqq.qlogo.cn/qqapp/101465933/FF34F311DDC43E2AF63BE897BCA24F05/100"><cite>NULL</cite></a></dd>
                            <dd><a href="javasript:;"><img src="https://thirdqq.qlogo.cn/qqapp/101465933/59AA25A7627284AE62C8E6EBDC6FE417/100"><cite>吓一跳</cite></a></dd>
                            <dd><a href="javasript:;"><img src="https://thirdqq.qlogo.cn/qqapp/101465933/28B021E0F5AF0A4B9B781A24329FE897/100"><cite>如初</cite></a></dd>
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
    if (!type) type = 0;
    layui.use('flow', function () {
        var $ = layui.jquery;
        var flow = layui.flow;
        flow.load({
            elem: '#LAY_bloglist',
            isLazyimg: true,
            done: function (page, next) {
                var list = [];
                $.get('<?= Url::to(['index/index'])?>', {type: type, page: page}, function (res) {
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
    });

    function jointSingleHtml(html, item) {
        html = `
            <section class="article-item zoomIn article">
                        <div class="fc-flag">${item.type}</div>
                        <h5 class="title">
                            <span class="fc-blue">【${item.flag}】</span>
                            <a href="${item.url}">${item.title}</a>
                        </h5>
                        <div class="time">
                            <span class="day">${item.day}</span>
                            <span class="month fs-18">${item.month}<span class="fs-14">月</span></span>
                            <span class="year fs-18 ml10">${item.year}</span>
                        </div>
                        <div class="content">
                            <a href="${item.url}" class="cover img-light">
                                <img lay-src="${item.pic}">
                            </a>
                            ${item.summary}
                        </div>
                        <div class="read-more">
                            <a href="${item.url}" class="fc-black f-fwb">继续阅读</a>
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
