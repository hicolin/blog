<?php
/**
 * User: Colin
 * Time: 2019/7/9 14:31
 */

namespace frontend\controllers;


use yii\helpers\VarDumper;
use yii\web\Controller;

class BaseController extends Controller
{
    /**
     * ajax 返回json数据
     * @param $status
     * @param $msg
     * @param string $data
     * @return string
     */
    public function json($status, $msg, $data = '')
    {
        if ($data) {
            return json_encode(['status' => $status, 'msg' => $msg, 'data' => $data], JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode(['status' => $status, 'msg' => $msg], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 调试函数（支持语法高亮）
     */
    public function dd(){
        $param = func_get_args();
        foreach ($param as $p)  {
            VarDumper::dump($p, 10, true);
        }
        exit(1);
    }
}
