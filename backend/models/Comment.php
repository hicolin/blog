<?php

namespace backend\models;

use backend\libs\Helper;

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

    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }

    public static function getTypes()
    {
        return [1 => '文章', 2 => '留言'];
    }

    public function mAddMessage($userId, $content, $ip, $pid, $toUserId, $articleId)
    {
        $this->user_id = $userId;
        $toUserId && $this->to_user_id = $toUserId;
        $pid && $this->pid = $pid;
        $articleId && $this->article_id = $articleId;
        $this->content = htmlspecialchars($content);
        $this->type = 2;
        $this->status = 1;
        $this->ip = $ip;
        $res = Helper::getCityByIp($ip);
        $this->location = $res['province'] . ' ' . $res['city'];
        $this->create_time = time();
        if (!$this->save()) {
            $error = array_values($this->getFirstErrors())[0];
            return $this->arrData(100, $error);
        }
        return $this->arrData(200, '添加成功');
    }

    public static function mFormatData($data)
    {
        foreach ($data as &$list) {
            $list['create_time'] = date('Y-m-d', $list['create_time']);
            $list['content'] = htmlspecialchars_decode($list['content']);
            $list['child'] = Comment::find()->joinWith('member')->joinWith('user')
                ->where(['colin_comment.status' => 1, 'colin_comment.type' => 2, 'colin_comment.pid' => $list['id']])
                ->orderBy('colin_comment.create_time asc')  // 二级评论按时间升序排列
                ->asArray()->all();
            foreach ($list['child'] as &$item) {
                $item['content'] = htmlspecialchars_decode($item['content']);
                $item['create_time'] = date('Y-m-d', $item['create_time']);
            }
        }
        return $data;
    }

}
