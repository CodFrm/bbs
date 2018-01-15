<?php
/**
 *============================
 * author:Farmer
 * time:2017/11/23
 * blog:blog.icodef.com
 * function:用户操作
 *============================
 */

namespace app\common\model;


use icf\lib\db;

class user {

    /**
     * 验证用户名
     * @author Farmer
     * @param $user
     * @return bool|string
     */
    public static function isUser($user, $my = null) {
        if ($um = self::getUser($user)) {
            if (is_null($my)) {
                return '用户名已经被注册';
            }
            return ($um['user'] == $my ?: '用户名已经被注册');
        } else {
            return true;
        }
    }

    /**
     * 通过 邮箱/用户名/uid 获取用户数据
     * @param $user
     * @return mixed
     */
    public static function getUser($user) {
        return db::table('users')->where('uid', $user)->_or()->where('username', $user)->_or()->where('email', $user)->find();
    }

    /**
     * 通过uid获取用户数据
     * @param $uid
     * @return mixed
     */
    public static function uidUser($uid) {
        return db::table('users')->where(['uid' => $uid])->find();
    }

    /**
     * 获取用户组信息
     * @author Farmer
     * @param $uid
     * @return array
     */
    public static function getGroup($uid) {
        if ($rec = db::table('usergroup as a|group as b')->order('group_auth')->where(['uid' => $uid, 'a.group_id=b.group_id'])->select()) {
            return $rec->fetchAll();
        }
        return [];
    }


    /**
     * 验证权限
     * @param $group_id
     * @param array $auth
     * @return bool
     */
    public static function verifyAuth($group_id, &$auth = array()) {
        $rec = db::table('groupauth as a|auth as b')->where(['group_id' => $group_id, 'a.auth_id=b.auth_id'])->select();
        $module = input('module');
        $ctrl = input('ctrl');
        $action = input('action');
        while ($msg = $rec->fetch()) {
            $auth[$msg['auth_interface']] = 1;
            if ($count = substr_count($msg['auth_interface'], '->')) {
                if ($count == 1) {
                    if (($module . '->' . $ctrl) == $msg['auth_interface']) {
                        return true;
                    }
                } else {
                    if (($module . '->' . $ctrl . '->' . $action) == $msg['auth_interface']) {
                        return true;
                    }
                }

            } else {
                if ($msg['auth_interface'] == $module) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 申请一个账号
     * @param $postData
     * @return bool
     */
    public static function applyUser($postData) {
        $ret = verify($postData, [
            'act' => ['func' => [
                function ($act, $email) {
                    if (verifyToken($act, $email, 1)) {
                        return true;
                    }
                    return '错误的令牌';
                }, 'email'], 'msg' => '错误的令牌'],
            'username' => ['func' => ['\app\common\model\user::isUser'], 'regex' => ['/^[\x{4e00}-\x{9fa5}\w\@\.]{2,16}$/u', '用户名格式错误'], 'msg' => '用户名不能为空', 'sql' => 'username'],
            'password' => ['regex' => ['/^[\\~!@#$%^&*()-_=+|{}\[\], .?\/:;\'\"\d\w]{6,16}$/', '密码不符合规范'], 'msg' => '请输入密码', 'sql' => 'password'],
            'email' => ['func' => ['\app\common\model\user::isEmail'], 'regex' => ['/^[\w\.]{1,16}@(qq\.com|163\.com|outlook\.com)$/', '邮箱格式错误'], 'msg' => '邮箱不能为空', 'sql' => 'email'],
        ], $data);
        if ($ret === true) {
            //添加用户
            $data['avatar'] = 'default.png';
            $data['reg_time'] = time();
            db::table('users')->insert($data);
            $uid = db::table()->lastinsertid();
            db::table('users')->where('uid', $uid)->update(['password' => user::encodePwd($uid, $data['password'])]);
            //添加权限
            db::table('usergroup')->insert(['uid' => $uid, 'group_id' => config('guest_auth')]);//给予游客的权限
            db::table('usergroup')->insert(['uid' => $uid, 'group_id' => config('member_auth')]);//给予注册的默认权限
            deleteToken($postData['act']);
            return true;
        }
        return $ret;
    }

    /**
     * 验证邮箱
     * @author Farmer
     * @param $user
     * @return bool|string
     */
    public static function isEmail($email) {
        if (self::getUser($email)) {
            return '邮箱已经被注册';
        } else {
            return true;
        }
    }

    /**
     * 编码密码
     * @author Farmer
     * @param $uid
     * @param $pwd
     * @return string
     */
    public static function encodePwd($uid, $pwd) {
        $str = hash('sha256', $uid . $pwd . config('pwd_encode_salt'));
        return $str;
    }

}