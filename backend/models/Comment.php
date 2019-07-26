<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "colin_comment".
 *
 * @property int $id 评论表
 * @property int $pid 父级ID
 * @property int $user_id 用户ID
 * @property int $to_user_id 回复用户ID
 * @property int $article_id 文章ID
 * @property string $content 内容
 * @property int $type 类型 1:文章 2:留言
 * @property int $status 状态 1:显示 2:隐藏
 * @property string $ip IP
 * @property string $location 位置
 * @property int $create_time 创建时间
 */
class Comment extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'colin_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'user_id', 'to_user_id', 'article_id', 'type', 'status', 'create_time'], 'integer'],
            [['content'], 'string'],
            [['ip'], 'string', 'max' => 20],
            [['location'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '父级ID',
            'user_id' => '用户ID',
            'to_user_id' => '回复用户ID',
            'article_id' => '文章ID',
            'content' => '内容',
            'type' => '类型',
            'status' => '状态',
            'ip' => 'IP',
            'location' => '位置',
            'create_time' => '创建时间',
        ];
    }

    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'user_id']);
    }

    public function getUser()
    {
        return $this->hasOne(Member::className(), ['id' => 'to_user_id'])->alias('colin_user');
    }

}
