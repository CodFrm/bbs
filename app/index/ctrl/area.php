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
use icf\lib\db;
use icf\lib\other\check;
use Michelf\Markdown;

class area extends authCtrl {

    public function post() {
        view()->display();
    }

    public function postPost() {
        $valid = new check();
        $ret = $valid->addData($_POST)->rule([
            'title:标题' => ['regex' => '/^.{3,64}$/', 'bind' => ''],
            'md:内容' => ['range' => [16, 10000], 'bind' => 'mkdown'],
            'area:分区' => ['func' => function ($aid) {
                //不能选择根分区
                if (db::table('area')->where('aid', $aid)->find()['father_aid'] == 0) {
                    return '不能选择根分区';
                }
                return true;
            }, 'bind' => 'aid']
        ])->check();
        if ($ret === true) {
            $html = Markdown::defaultTransform($valid->getBindVar()['mkdown']);
            $data = $valid->getBindVar();
            $data['html'] = $html;
            $data['time'] = time();
            $data['uid'] = $_COOKIE['uid'];
            db::table('posts')->insert($data);
            return ['code' => 0, 'msg' => '发帖成功,3秒后跳转到帖子', 'tid' => db::table()->lastinsertid()];
        }
        return ['code' => -1, 'msg' => $ret];
    }

    public function getArea($aid = 0) {
        $rows = db::table('area')->field('aid,title')->where('father_aid', $aid)->select()->fetchAll();
        return $rows;
    }
}