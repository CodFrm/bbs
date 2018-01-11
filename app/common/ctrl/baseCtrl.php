<?php
/**
 *============================
 * author:Farmer
 * time:2018/1/10
 * blog:blog.icodef.com
 * function:基础控制器
 *============================
 */


namespace app\common\ctrl;


class baseCtrl {
    public function __construct() {
        view()->assign('title', config('title'));
    }
}