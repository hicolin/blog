<?php

namespace backend\models;

use Yii;
/**
 * This is the model class for table "colin_member".
 *
 * @property string $id 前台用户表
 * @property string $nickname 昵称
 * @property string $qq QQ
 * @property string $avatar 头像
 * @property int $status 状态 1:启用 2:禁用
 * @property int $create_time 创建时间
 */
class Member extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'colin_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'create_time'], 'integer'],
            [['nickname'], 'string', 'max' => 100],
            [['qq'], 'string', 'max' => 15],
            [['avatar'], 'string', 'max' => 255],
            [['qq'], 'unique']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nickname' => '昵称',
            'qq' => 'QQ',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }

    public function create($data)
    {
        $this->nickname = $data['nickname'];
        $this->qq = $data['qq'];
        $this->status = $data['status'];
        $this->create_time = time();
        if (!$this->save()) {
            $error = array_values($this->getFirstErrors())[0];
            return $this->arrData(100, $error);
        }
        return $this->arrData(200, '添加成功');
    }

    public function edit($data)
    {
        $model = self::findOne($data['id']);
        $model->nickname = $data['nickname'];
        $model->qq = $data['qq'];
        $model->status = $data['status'];
        if (!$model->save()) {
            $error = array_values($model->getFirstErrors())[0];
            return $this->arrData(100, $error);
        }
        return $this->arrData(200, '更新成功');
    }

    public function mAdd($qq, $nickname, $avatar)
    {
        $this->qq = $qq;
        $this->nickname = $nickname;
        $this->avatar = $avatar;
        $this->status = 1;
        $this->create_time = time();
        if (!$this->save()) {
            $error = array_values($this->getFirstErrors())[0];
            return $this->arrData(100, $error);
        }
        return $this->arrData(200, '添加成功');
    }


}
