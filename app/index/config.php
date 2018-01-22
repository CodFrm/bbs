<?php
/**
 *============================
 * author:Farmer
 * time:2018/1/6
 * blog:blog.icodef.com
 * function:
 *============================
 */

return [
    'rest' => true,
    'route' => [
        '*' => [
            'users#p' => 'index->index->users',
            'test' => 'login->index',
            't/{$tid}/{$page}.html'=>'index->index->article'
        ]
    ]
];