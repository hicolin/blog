function jointSingleHtml(html, item) {
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
                        <a href="javascript:;" class="btn-reply" data-id="${item.user_id}" data-pid="${item.id}"
                        data-nickname="${item.member.nickname}" >回复</a>
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
                        <a href="javascript:;" class="btn-reply" data-id="${val.user_id}" data-pid="${item.id}"
                         data-nickname="${val.member.nickname}">回复</a>
                    </p>
                </div>
                `;
        })
    }
    html += `
                <div class="replycontainer layui-hide">
                    <form class="layui-form" action="">
                        <div class="layui-form-item">
                            <textarea name="replyContent"  placeholder="请输入回复内容" class="layui-textarea" style="min-height:80px;"></textarea>
                        </div>
                        <div class="layui-form-item">
                            <button class="layui-btn layui-btn-xs" type="button">提交</button>
                        </div>
                    </form>
                </div>
            </li>
    `;
    return html;
}

function jointSecondHtml(html, val, targetPid) {
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
                <a href="javascript:;" class="btn-reply" data-id="${val.user_id}" data-pid="${targetPid}" data-nickname="${val.member.nickname}">回复</a>
            </p>
        </div>
        `;
    return html;
}
