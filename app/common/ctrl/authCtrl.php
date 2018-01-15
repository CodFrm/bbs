<?php
/**
 *============================
 * author:Farmer
 * time:2017/11/23
 * blog:blog.icodef.com
 * function:权限控制器
 *============================
 */

namespace app\common\ctrl;

use app\common\model\user;
use app\index\ctrl\index;
use icf\lib\db;

class authCtrl extends baseCtrl {
    protected $userMsg;

    public function __construct() {
        parent::__construct();
        if (!isLogin()) {
            //没有登录给予游客权限
            $_COOKIE['uid'] = -1;
            $this->userMsg['group'] = [db::table('group')->where(['group_id' => config('guest_auth')])->find()];
        } else {
            $this->userMsg = user::uidUser($_COOKIE['uid']);
            $this->userMsg['group'] = user::getGroup($_COOKIE['uid']);
            view()->assign('um',$this->userMsg);
        }
        $auth = false;
        foreach ($this->userMsg['group'] as $item) {
            if (user::verifyAuth($item['group_id'], $this->userMsg['auth'])) {
                $auth = true;
                break;
            }
        }
        if ($auth !== true) {
            index::error('你没有权限访问本页面,或者系统出现了错误', [__HOME_, '首页'], '权限错误');
            exit();
        }
    }

}