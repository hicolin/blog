<?php
/**
 * User: Colin
 * Time: 2019/8/8 8:57
 */

namespace frontend\controllers;


use backend\models\Article;
use Yii;

class Service extends BaseController
{
    public static function getCateTitle($type)
    {
        $typeArr = [1 => '后端', 2 => '前端', 3 => '运维', 4 => '杂项'];
        if (!$type) {
            return '欢迎您的到来';
        } else {
            return $typeArr[$type];
        }
    }

    public static function addImgUlr($content)
    {
        $content = htmlspecialchars_decode($content);
        $imgDomain = Yii::$app->params['imgDomain'];
        $content = preg_replace('/<img.*?src="(.*?)"/is', "<img src={$imgDomain}$1", $content);
        return $content;
    }

    public static function getRelationArticle($id)
    {
        $prevArticle = Article::find()->where(['status' => 1])->andWhere(['>', 'id' , $id])
            ->orderBy('create_time asc')->limit(1)->asArray()->one();
        $nextArticle = Article::find()->where(['status' => 1])->andWhere(['<', 'id' , $id])
            ->orderBy('create_time desc')->limit(1)->asArray()->one();
        $relationArticle = compact('prevArticle', 'nextArticle');
        return $relationArticle;
    }

    // 保存QQ头像 (生成速度慢)
    public static function saveAvatar($qq, $qqAvatar) {
        // $avatarPath = Yii::getAlias('@web/uploads/avatar/');    // 这种方法会出现权限错误： mkdir(): Permission denied
        $avatarPath = 'uploads/avatar/';
        is_dir($avatarPath) || mkdir($avatarPath, 0777, true);
        $avatarFile = $qq . '.jpg';
        $storeAvatar = $avatarPath . $avatarFile;
        file_put_contents($storeAvatar, file_get_contents($qqAvatar));
        return $storeAvatar;
    }


}
