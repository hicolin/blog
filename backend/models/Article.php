<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "colin_article".
 *
 * @property int $id 文章表
 * @property string $title 标题
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
            [['title'], 'string', 'max' => 255],
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
}
