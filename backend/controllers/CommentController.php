<?php
/**
 * User: Colin
 * Time: 2019/2/2 17:44
 */

namespace backend\controllers;

use backend\models\Comment;
use Yii;
use yii\data\Pagination;

class CommentController extends BaseController
{
    public function actionIndex()
    {
        $query = Comment::find()->joinWith('member')
            ->joinWith('user')->joinWith('article');
        $search = Yii::$app->request->get('search');
        $query = $this->condition($query, $search);
        $countQuery = clone $query;
        $pagination = new Pagination([
           'totalCount' => $countQuery->count(),
           'defaultPageSize' => 10,
        ]);
        $models = $query
            ->orderBy('id desc')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $frontArticleUrl = Yii::$app->params['frontDomain'] . '/index/article?id=';
        $frontMessageUrl = Yii::$app->params['frontDomain'] . '/index/message';
        $typeArr = Comment::getTypes();
        $data = compact('models', 'pagination', 'search', 'frontArticleUrl', 'frontMessageUrl', 'typeArr');
        return $this->render('index', $data);
    }

    public function condition($query, $search)
    {
        if (isset($search['nickname']) && $search['nickname']) {
            $query = $query->andWhere(['like', 'nickname', $search['nickname']]);
        }
        if (isset($search['qq']) && $search['qq']) {
            $query = $query->andWhere(['like', 'qq', $search['qq']]);
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

    public function actionChangeStatus()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $Comment = Comment::findOne($post['id']);
            $status = $post['status'] == 1 ? 2 : 1;
            $Comment->status = $status;
            if (!$Comment->save(false)){
                return $this->json(100, '操作失败');
            }
            return $this->json(200, '操作成功');
        }
    }

    public function actionDel()
    {
        $id = (int)Yii::$app->request->get('id');
        $model = Comment::findOne($id);
        $res = $model->delete();
        if (!$res) {
            return $this->json(100, '删除失败');
        }
        return $this->json(200, '删除成功');
    }

    public function actionBatchDel()
    {
        $idArr = Yii::$app->request->get('idArr');
        $res = Comment::deleteAll(['in', 'id', $idArr]);
        if (!$res) {
            return $this->json(100, '批量删除失败');
        }
        return $this->json(200, '批量删除成功');
    }


}
