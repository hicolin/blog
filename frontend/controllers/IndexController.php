<?php
/**
 * User: Colin
 * Date: 2019/7/5
 * Time: 23:34
 */

namespace frontend\controllers;


use backend\libs\Util;
use backend\models\Article;
use backend\models\Comment;
use backend\models\Member;
use Yii;

class IndexController extends BaseController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $type = (int)Yii::$app->request->get('type');
        $page = (int)Yii::$app->request->get('page');
        if (!$page) $page = 1;
        if ($type && !in_array($type, range(1,4))) {
            return $this->redirect('/');
        }
        $this->view->title = $this->getCateTitle($type);

        if (Yii::$app->request->isAjax) {
            $query = Article::find()->where(['status' => 1])->orderBy('create_time desc');
            if ($type) $query->andWhere(['type' => $type]);
            $count = $query->count();
            $pageSize = 5;
            $pages = ceil($count / $pageSize);
            $offset = ($page - 1) * $pageSize;
            $data = $query->offset($offset)->limit($pageSize)->asArray()->all();
            $data = Article::mFormatData($data);
            $res = compact('data', 'pages');
            return $this->json(200, 'ok', $res);
        }

        $articleNum = [];
        $articleNum['all'] = Article::find()->where(['status' => 1])->count();
        $articleNum['backend'] = Article::find()->where(['status' => 1, 'type' => 1])->count();
        $articleNum['frontend'] = Article::find()->where(['status' => 1, 'type' => 2])->count();
        $articleNum['linux'] = Article::find()->where(['status' => 1, 'type' => 3])->count();
        $articleNum['other'] = Article::find()->where(['status' => 1, 'type' => 4])->count();

        return $this->render('index', compact('type', 'articleNum'));
    }

    public function actionArticle()
    {
        if (Yii::$app->request->isAjax) {
            $page = (int)Yii::$app->request->get('page');
            $articleId = Yii::$app->request->get('article_id');
            $type = 1;
            $query = Comment::find()->joinWith('member')->joinWith('user')
                ->where(['colin_comment.status' => 1,'colin_comment.type' => 1,
                    'colin_comment.pid' => 0, 'colin_comment.article_id' => $articleId])
                ->orderBy('colin_comment.create_time desc');
            $count = $query->count();
            $pageSize = 10;
            $pages = ceil($count / $pageSize);
            $offset = ($page - 1) * $pageSize;
            $data = $query->offset($offset)->limit($pageSize)->asArray()->all();
            $data = Comment::mFormatData($data, $type, $articleId);
            $res = compact('data', 'pages');
            return $this->json(200, 'ok', $res);
        }
        $id = (int)Yii::$app->request->get('id');
        $article = Article::findOne($id);
        if (!$article) return $this->redirect('/');
        $this->view->title = $article->title;
        return $this->render('article', compact('article', 'id'));
    }

    public function actionMessage()
    {
        $this->view->title = '留言';
        if (Yii::$app->request->isAjax) {
            $page = (int)Yii::$app->request->get('page');
            $type = 2;
            $articleId = null;
            $query = Comment::find()->joinWith('member')->joinWith('user')
                ->where(['colin_comment.status' => 1,'colin_comment.type' => 2, 'colin_comment.pid' => 0])
                ->orderBy('colin_comment.create_time desc');
            $count = $query->count();
            $pageSize = 10;
            $pages = ceil($count / $pageSize);
            $offset = ($page - 1) * $pageSize;
            $data = $query->offset($offset)->limit($pageSize)->asArray()->all();
            $data = Comment::mFormatData($data, $type, $articleId);
            $res = compact('data', 'pages');
            return $this->json(200, 'ok', $res);
        }
        return $this->render('message');
    }

    public function actionAddMessage()
    {
        if (Yii::$app->request->isPost) {
            $qq = Yii::$app->request->post('qq');
            $content = Yii::$app->request->post('content');
            $pid = Yii::$app->request->post('pid');
            $articleId = Yii::$app->request->post('article_id');
            $toUserId = Yii::$app->request->post('to_user_id');
            $type = (int)Yii::$app->request->post('type');

            if ($qq == '811687790') {
                return $this->json(100, '博主QQ号，禁止他人使用');
            }
            $qqArr = explode('@', $qq);
            if ($qqArr[0] == '811687790' && strtolower($qqArr[1]) == 'hicolin') {
                $qq = $qqArr[0];
            }
            if (!preg_match('/^\d{5,12}$/', $qq)) {
                return $this->json(100, 'QQ号码不正确');
            }

            $member = Member::findOne(['qq' => $qq]);
            if (!$member) {
                $qqInfo = Util::getUserInfoByQq($qq);
//                $qqAvatar = $qqInfo[$qq][0];
                $qqNickname = trim($qqInfo[$qq][6]);
                if (!$qqInfo || !$qqNickname) {
                    return $this->json(200, 'QQ号码不正确');
                }

                // $storeAvatar = $this->saveAvatar($qq, $qqAvatar);
                // $qqAvatar: http://qlogo3.store.qq.com/qzone/811687790/811687790/100  (加了图片防盗链，不能直接引用)
                // 没加防盗链的头像地址 http://q1.qlogo.cn/g?b=qq&nk=494942200&s=100
                $storeAvatar = "http://q1.qlogo.cn/g?b=qq&nk={$qq}&s=100";
                $memberModel = new Member();
                $res = $memberModel->mAdd($qq, $qqNickname, $storeAvatar);
                if ($res['status'] != 200) {
                    return $this->json(100, $res['msg']);
                }
                $member = Member::findOne(['qq' => $qq]);
            }
            $ip = Yii::$app->request->getUserIP();
            $comment = new Comment();
            $res = $comment->mAddMessage($member->id, $content, $ip, $pid, $toUserId, $articleId, $type);
            if ($res['status'] != 200) {
                return $this->json(100, $res['msg']);
            }
            $data = Comment::find()->joinWith('member')->joinWith('user')
                ->where(['colin_comment.id' => $comment->id])
                ->asArray()->all();
            $data = Comment::mFormatData($data, $type, $articleId);
            return $this->json(200, '留言成功', $data);
        }
    }

    public function actionAbout()
    {
        $this->view->title = '关于';
        return $this->render('about');
    }

    public function actionError()
    {
        return $this->redirect('/');
    }

    protected function getCateTitle($type) {
        $typeArr = [1 => '后端', 2 => '前端', 3 => '运维', 4 => '杂项'];
        if (!$type) {
            return '欢迎您';
        } else {
            return $typeArr[$type];
        }
    }

    // 保存QQ头像
    protected function saveAvatar($qq, $qqAvatar) {
        // $avatarPath = Yii::getAlias('@web/uploads/avatar/');    // 这种方法会出现权限错误： mkdir(): Permission denied
        $avatarPath = 'uploads/avatar/';
        is_dir($avatarPath) || mkdir($avatarPath, 0777, true);
        $avatarFile = $qq . '.jpg';
        $storeAvatar = $avatarPath . $avatarFile;
        file_put_contents($storeAvatar, file_get_contents($qqAvatar));
        return $storeAvatar;
    }
}
