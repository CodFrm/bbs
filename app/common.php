<?php
/**
 *============================
 * author:Farmer
 * time:2017/11/21
 * blog:blog.icodef.com
 * function:公共函数库
 *============================
 */


function _js($file) {
    return '<script type="text/javascript" src="' . __HOME_ . '/static/js/' . $file . '.js"></script>';
}

function _css($file) {
    return '<link rel="stylesheet" href="' . __HOME_ . '/static/css/' . $file . '.css">';
}

function _img($file) {
    return __HOME_ . '/static/images/' . $file;
}
function _avatar($file) {
    return __HOME_ . '/static/images/avatar/' . $file;
}
/**
 * 对变量进行验证
 * @author Farmer
 * @param $array
 * @param $mode
 * @return bool
 */
function verify($array, $mode, &$data = '') {
    foreach ($mode as $key => $value) {
        if (is_string($value)) {
            if (empty($array[$key])) {
                return $value;
            }
        } else if (is_array($value)) {
            if (empty($array[$key])) {
                return $value['msg'];
            }
            if (!empty($value['regex'])) {//正则
                if (!preg_match($value['regex'][0], $array[$key])) {
                    return $value['regex'][1];
                }
            }
            if (!empty($value['func'])) {//对函数处理
                $tmpFunction = $value['func'];
                $funName = $value['func'][0];
                $parameter = array();
                unset($tmpFunction[0]);
                $parameter[] = $array[$key];
                foreach ($tmpFunction as $v) {
                    $parameter[] = $array[$v];
                }
                $tmpValue = call_user_func_array($funName, $parameter);
                if ($tmpValue !== true) {
                    return $tmpValue;
                }
            }
            if (!empty($value['enum'])) {//判断枚举类型
                if (!in_array($array[$key], $value['enum'][0])) {
                    return $value['enum'][1];
                }
            }
            if (!empty($value['sql'])) {//将其复制给sql插入数组
                $data[$value['sql']] = $array[$key];
            }
        }
    }
    return true;
}

/**
 * 取随机字符串
 * @author Farmer
 * @param $length
 * @param $type
 * @return string
 */
function getRandString($length, $type = 2) {
    $randString = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
    $retStr = '';
    $type = 9 + $type * 26;
    for ($n = 0; $n < $length; $n++) {
        $retStr .= substr($randString, mt_rand(0, $type), 1);
    }
    return $retStr;
}


function sendEmail($to, $title, $content) {
    $smtpserver = "smtp.exmail.qq.com";//SMTP服务器
    $smtpserverport = 465;//SMTP服务器端口
    $smtpusermail = "love@icodef.com";//SMTP服务器的用户邮箱
    $smtpemailto = $to;//发送给谁
    $smtpuser = "love@icodef.com";//SMTP服务器的用户帐号(或填写new2008oh@126.com，这项有些邮箱需要完整的)
    $emailname = "信院小站";
    $smtppass = "VAhBsdKFPUf53QZc";//SMTP服务器的用户密码
    $mailtitle = $title;//邮件主题
    $mailcontent = $content;//邮件内容
    $smtp = new icf\lib\info\smtp();
    $smtp->setName($emailname);
    $smtp->setServer($smtpserver, $smtpusermail, $smtppass, $smtpserverport, true); //设置smtp服务器，到服务器的SSL连接
    $smtp->setFrom($smtpuser); //设置发件人
    $smtp->setReceiver($smtpemailto); //设置收件人，多个收件人，调用多次
    $smtp->setMail($mailtitle, $mailcontent); //设置邮件主题、内容
    return $smtp->sendMail(); //发送
}

/**
 * 创建一个验证令牌
 * @param $val
 * @param int $type
 * @return string
 */
function createToken($val, $type = 0) {
    $len = 16;
    if ($type == 1) {
        $len = 64;
        \icf\lib\db::table('token')->where(['type' => 1, 'value' => $val])->delete();
    }
    $token = '';
    do {
        $token = getRandString($len, 2);
    } while (\icf\lib\db::table('token')->where('token', $token)->count());
    \icf\lib\db::table('token')->insert(['token' => $token, 'value' => $val, 'time' => time(), 'type' => $type]);
    return $token;
}

/**
 * 验证token是否有效,返回token数量
 * @param $token
 * @param $val
 * @param int $type
 * @return mixed
 */
function verifyToken($token, $val, $type = 0) {
    $db = \icf\lib\db::table('token');
    $db->where(['token' => $token, 'value' => $val]);
    if ($type == 1) {
        $db->where('time', time() - 1800, '>');//30分钟有效期
    } else {
        $db->where('time', time() - 432000, '>');//5天有效期
    }
    return $db->count();
}

/**
 * 删除令牌
 * @param $token
 */
function deleteToken($token) {
    \icf\lib\db::table('token')->where('token', $token)->delete();
}

/**
 * 获取/设置配置
 * @author Farmer
 * @param $key
 * @param string $value
 * @return int
 */
function config($key, $value = '') {
    if (!empty($value)) {
        if (config($key) !== false) {
            return \icf\lib\db::table('config')->where(['key' => $key])->update(['value' => $value]);
        } else {
            return \icf\lib\db::table('config')->insert(['value' => $value, 'key' => $key]);
        }
    } else {
        $rec = \icf\lib\db::table('config')->where(['key' => $key])->find();
        if (!$rec) {
            return false;
        }
        return $rec['value'];
    }
}

/**
 * 判断是否登陆
 * @return bool|mixed
 */
function isLogin() {
    if ($uid = _cookie('uid') && $token = _cookie('token')) {
        return verifyToken(_cookie('token'), _cookie('uid'));
    }
    return false;
}

/**
 * 取出url中的路径
 * @return bool|string
 */
function getUrlRoot(){
    return substr($_SERVER['SCRIPT_NAME'],0,strrpos($_SERVER['SCRIPT_NAME'],'/'));
}