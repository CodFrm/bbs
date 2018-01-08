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


class login {

    public function index() {
        view()->display('login');
    }

    public function register() {
        view()->display();
    }
}