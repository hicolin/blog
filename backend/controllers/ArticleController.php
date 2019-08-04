<?php
/**
 * User: Colin
 * Date: 2019/7/14
 * Time: 19:53
 */

namespace backend\controllers;

use backend\models\Article;
use Yii;
use yii\data\Pagination;

class ArticleController extends BaseController
{
    public function actionIndex()
    {
        $query = Article::find();
        $search = Yii::$app->request->get('search');
        $query = $this->condition($query, $search);
        $pagination = new Pagination([
            'totalCount' => $query->count(),
            'defaultPageSize' => 10,
        ]);
        $models = $query
            ->orderBy('id desc')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $flagArr = Article::getFlags();
        $typeArr = Article::getTypes();
        $statusArr = Article::getStatus();
        $frontArticleUrl = Yii::$app->params['frontDomain'] . '/index/article?id=';
        $data = compact('models', 'pagination', 'search', 'flagArr', 'typeArr', 'statusArr', 'frontArticleUrl');
        return $this->render('index', $data);
    }

    public function condition($query, $search)
    {
        if (isset($search['title']) && $search['title']) {
            $query = $query->andWhere(['like', 'title', $search['title']]);
        }
        if (isset($search['type']) && $search['type']) {
            $query = $query->andWhere(['type' => $search['type']]);
        }
        if (isset($search['status']) && $search['status']) {
            $query = $query->andWhere(['status' => $search['status']]);
        }
        if (isset($search['b_time']) && $search['b_time']) {
            $bTime = strtotime($search['b_time'] . ' 00:00:00');
            $query = $query->andWhere(['>=', 'create_time', $bTime]);
        }
        if (isset($search['e_time']) && $search['e_time']) {
            $eTime = strtotime($search['e_time'] . ' 23:59:59');
            $query = $query->andWhere(['<=', 'create_time', $eTime]);
        }
        return $query;
    }

    public function actionCreate()
    {
        $flagArr = Article::getFlags();
        $typeArr = Article::getTypes();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $img = $post['thumb'];
            if (stristr($img, 'data:image/')) {
                self::dd(1);
            }
            self::dd(2);
            $model = new Article();
            $res = $model->create($post);
            if ($res['status'] != 200) {
                return $this->json(100, $res['msg']);
            }
            return $this->json(200, $res['msg']);
        }
        return $this->render('create', compact('flagArr', 'typeArr'));
    }

    public function actionUpdate()
    {
        $id = Yii::$app->request->get('id');
        $model = Article::findOne($id);
        $flagArr = Article::getFlags();
        $typeArr = Article::getTypes();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $model = new Article();
            $res = $model->edit($post);
            if ($res['status'] != 200) {
                return $this->json(100, $res['msg']);
            }
            return $this->json(200, $res['msg']);
        }
        return $this->render('update', compact('model', 'flagArr', 'typeArr'));
    }

    public function actionDel()
    {
        $id = (int)Yii::$app->request->get('id');
        $model = Article::findOne($id);
        $res = $model->delete();
        if (!$res) {
            return $this->json(100, '删除失败');
        }
        return $this->json(200, '删除成功');
    }

    public function actionBatchDel()
    {
        $idArr = Yii::$app->request->get('idArr');
        $res = Article::deleteAll(['in', 'id', $idArr]);
        if (!$res) {
            return $this->json(100, '批量删除失败');
        }
        return $this->json(200, '批量删除成功');
    }

}
