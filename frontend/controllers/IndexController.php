<?php
/**
 * User: Colin
 * Date: 2019/7/5
 * Time: 23:34
 */

namespace frontend\controllers;


use Yii;

class IndexController extends BaseController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $type = (int)Yii::$app->request->get('type');
        if ($type && !in_array($type, range(1,4))) return $this->redirect('/');
        $this->view->title = $this->getCateTitle($type);
        return $this->render('index');
    }

    public function actionArticle()
    {
        $this->view->title = '文章标题';
        return $this->render('article');
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
