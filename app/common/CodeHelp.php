<?php
/**
 *============================
 * author:Farmer
 * time:2018/1/9
 * blog:blog.icodef.com
 * function:状态码
 *============================
 */

namespace app\common;


class CodeHelp {
    public static function getStatus($code) {
        return ['code' => $code, 'msg' => call_user_func('app\common\CodeHelp::' . input('ctrl') . 'Code', $code)];
    }

    private static function loginCode($code) {
        $help = [
            0 => 'success',
            10010 => '错误的邮箱格式'
        ];
        return $help[$code];
    }
}