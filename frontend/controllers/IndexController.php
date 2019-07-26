<?php
/**
 * User: Colin
 * Date: 2019/7/5
 * Time: 23:34
 */

namespace frontend\controllers;


use backend\models\Article;
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
        $this->view->title = '文章标题';
        $id = (int)Yii::$app->request->get('id');
        $article = Article::findOne($id);
        if (!$article) return $this->redirect('/');
        return $this->render('article', compact('article'));
    }

    public function actionMessage()
    {
        $this->view->title = '留言';
        return $this->render('message');
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
            return '首页';
        } else {
            return $typeArr[$type];
        }
    }
}
