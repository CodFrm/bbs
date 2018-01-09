<?php
/**
 *============================
 * author:Farmer
 * time:2018/1/8
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace app\index\ctrl;

use app\common\CodeHelp;

define('ERROR_EMAIL', 10010);

class login {
    public function index() {
        view()->display('login');
    }

    public function register() {
        view()->display();
    }

    public function email($email) {
        $ret = verify($_GET, [
            'email' => ['regx' => ['/^[\w\.]{1,16}@(qq\.com|163\.com|outlook\.com)$/', ERROR_EMAIL], 'msg' => '必须输入邮箱']
        ]);
        if ($ret === true) {
            return [];
        }
        return CodeHelp::getStatus($ret);
    }
}