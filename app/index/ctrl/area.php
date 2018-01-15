<?php
/**
 *============================
 * author:Farmer
 * time:2018/1/15
 * blog:blog.icodef.com
 * function:
 *============================
 */


namespace app\index\ctrl;


use app\common\ctrl\authCtrl;

class area extends authCtrl {

    public function post(){
        view()->display();
    }
}