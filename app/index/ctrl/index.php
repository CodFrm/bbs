<?php
/**
 *============================
 * author:Farmer
 * time:2017/11/7 19:22
 * blog:blog.icodef.com
 * function:首页
 *============================
 */

namespace app\index\ctrl;


use app\common\ctrl\authCtrl;

class index extends authCtrl {
    public function index() {
        view()->display();
//        return '我是首页233';
    }

    public static function error($error, $to, $errType) {
        view()->setModule('index');
        \view()->assign('error', $error);
        $arr = [];
        if (!is_array($to)) {
            $arr[0] = $to;
            $arr[1] = $to;
        } else {
            $arr = $to;
        }
        if (empty($errType)) $errType = '发生错误';
        \view()->assign('to', $arr);
        \view()->assign('errType', $errType);
        \view()->display('index/error');
    }
}