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

use app\common\ctrl\baseCtrl;
use app\common\model\user;


class login extends baseCtrl {

    protected function errorCode($code, $error = '') {
        //方便前端通过错误代码提示错误
        $errorCode = [
            '登录成功' => 0,
            '用户名已经被注册' => 10001,
            '用户名不能为空' => 10002,
            '用户不存在' => 10003,
            '密码不符合规范' => 10004,
            '密码错误' => 10005,
            '请输入密码' => 10006,
            '错误的令牌' => 10020
        ];
        if (empty($error)) {
            $error = $code;
            $code = -1;
        }
        if (isset($errorCode[$error])) $code = $errorCode[$error];
        return ['code' => $code, 'msg' => $error];
    }

    public function index() {
        view()->display('login');
    }

    public function login() {
        $ret = verify($_GET, [
            'username' => ['msg' => '用户名不能为空', 'sql' => 'username'],
            'password' => ['regex' => ['/^[\\~!@#$%^&*()-_=+|{}\[\], .?\/:;\'\"\d\w]{6,16}$/', '密码不符合规范'], 'msg' => '请输入密码', 'sql' => 'password']
        ], $data);
        if ($ret === true) {
            if ($userMsg = user::getUser($_GET['username'])) {
                if (user::encodePwd($userMsg['uid'], $_GET['password']) == $userMsg['password']) {
                    setcookie('token', createToken($userMsg['uid']), time() + 432000, getUrlRoot());
                    setcookie('uid', $userMsg['uid'], time() + 432000, getUrlRoot());
                    $ret = '登录成功';
                } else {
                    $ret = '密码错误';
                }
            } else {
                $ret = '用户不存在';
            }
        }
        return self::errorCode($ret);
    }

    public function register() {
        view()->display();
    }

    public function email($email) {
        $ret = verify($_GET, [
            'email' => ['func' => ['\app\common\model\user::isEmail'], 'regx' => ['/^[\w\.]{1,16}@(qq\.com|163\.com|outlook\.com)$/', '错误的邮箱格式'], 'msg' => '邮箱不能为空']
        ]);
        if ($ret === true) {
            $token = createToken($email, 1);
            $url = 'http:' . url('verify_reg', ['act' => $token, 'email' => $email]);
            set_time_limit(0);
            sendEmail($email, '欢迎注册 - ' . config('title'), '<a href="' . $url . '">点击前往注册</a><br>或者复制此链接:' . $url);
            return self::errorCode(0, '邮件已发送');
        }
        return self::errorCode($ret);
    }

    public function verify_reg($act, $email) {
        if (verifyToken($act, $email, 1)) {
            view()->display();
        } else {
            index::error('错误的验证代码!可能是过期了,或者已经使用,请去注册页面重新注册', [url('register'), '注册页面'], '验证错误!');
        }
    }

    public function reg() {
        $ret = user::applyUser($_POST);
        if ($ret === true) {
            return $this->errorCode(0, '注册成功');
        }
        return $this->errorCode($ret);
    }

}