<?php

namespace backend\models;

use backend\libs\Helper;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "colin_article".
 *
 * @property int $id 文章表
 * @property string $title 标题
 * @property string $thumb 缩略图
 * @property string $content 内容
 * @property int $flag 标识 1:原创 2:转载
 * @property int $type 类型 1:后端 2:前端 3:运维 4:杂项
 * @property string $keyword 关键字
 * @property int $status 状态 1:显示 2: 隐藏
 * @property int $view_num 浏览量
 * @property int $comment_num 评论数
 * @property int $create_time 创建时间
 */
class Article extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'colin_article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['flag', 'type', 'status', 'view_num', 'comment_num', 'create_time'], 'integer'],
            [['title', 'thumb'], 'string', 'max' => 255],
            [['keyword'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'thumb' => '缩略图',
            'content' => '内容',
            'flag' => '标识',
            'type' => '类型',
            'keyword' => '关键词',
            'status' => '状态',
            'view_num' => '阅读数',
            'comment_num' => '评论数',
            'create_time' => '创建时间',
        ];
    }

    public static function getFlags()
    {
        $flags = [1 => '原创', 2 => '转载'];
        return $flags;
    }

    public static function getTypes()
    {
        $types = [1 => '后端', 2 => '前端', 3 => '运维', 4 => '杂项'];
        return $types;
    }

    public static function getStatus()
    {
        $status = [1 => '显示', 2 => '隐藏'];
        return $status;
    }

    public function create($data)
    {
        $this->title = $data['title'];
        $this->keyword = $data['keyword'];
        $this->flag = $data['flag'];
        $this->type = $data['type'];
        $this->status = $data['status'];
        $this->content = htmlspecialchars($data['content']);
        $this->view_num = 0;
        $this->comment_num = 0;
        $this->create_time = time();
        $data['thumb'] && $this->thumb = $data['thumb'];
        if (!$this->save()) {
            $error = array_values($this->getFirstErrors())[0];
            return $this->arrData(100, $error);
        }
        return $this->arrData(200, '添加成功');
    }

    public function edit($data)
    {
        $model = self::findOne($data['id']);
        $originThumb = $model->thumb;
        $model->title = $data['title'];
        $model->keyword = $data['keyword'];
        $model->flag = $data['flag'];
        $model->type = $data['type'];
        $model->status = $data['status'];
        $model->content = htmlspecialchars($data['content']);
        $data['thumb'] && $model->thumb = $data['thumb'];
        if (!$model->save()) {
            $error = array_values($model->getFirstErrors())[0];
            return $this->arrData(100, $error);
        }
        $data['thumb'] && @unlink($originThumb);
        return $this->arrData(200, '更新成功');
    }


    public static function mFormatData($data)
    {
        $len = 300;
        $detect = new \Mobile_Detect();
        if ($detect->isMobile()) $len = 120;
        $flagArr = self::getFlags();
        $typeArr = self::getTypes();
        foreach ($data as &$list) {
            $list['url'] = Url::to(['index/article', 'id' => $list['id']]);
            $list['pic'] = Yii::$app->params['imgDomain'] . '/' . $list['thumb'];
            $list['summary'] = Helper::extractHtmlData($list['content'], $len);
            $list['year'] = date('Y', $list['create_time']);
            $list['month'] = date('m', $list['create_time']);
            $list['day'] = date('d', $list['create_time']);
            $list['flag'] = $flagArr[$list['flag']];
            $list['type'] = $typeArr[$list['type']];
        }
        return $data;
    }

}
