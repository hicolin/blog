<?php
/**
 * User: Colin
 * Date: 2019/7/5
 * Time: 23:34
 */

namespace frontend\controllers;


use yii\web\Controller;

class IndexController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionArticle()
    {
        return $this->render('article');
    }

    public function actionMessage()
    {
        return $this->render('message');
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

}