<?php
/**
 *============================
 * author:Farmer
 * time:2018/1/11
 * blog:blog.icodef.com
 * function:后台管理员控制器
 *============================
 */


namespace app\admin\ctrl;


use app\common\authCtrl;

class index extends authCtrl {

    public function index(){
        echo '我是后台emmm';
    }
}