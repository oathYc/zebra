<?php

use think\Env;

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
$sqlSer = require_once('sqlserver.php');
return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------
    // 当前系统版本
    'version'           => Env::get('config.version', 'v1.0.1'),

    // 加密盐
    'salt'              => Env::get('config.salt', '~shmilylbelva@#'),

    // socket server
    'socket'            => Env::get('config.socket', '127.0.0.1:7272'),

    // 管理员登录时间
    'save_time'         => Env::get('config.save_time', 86400),

    /*sqlser 数据库配置*/
    'sqlSer'            => $sqlSer,


    // 应用命名空间
    'app_namespace'     => 'app',
    // 应用调试模式
    'app_debug'         => true,
    // 应用Trace
    'app_trace'         => false,

    //允许访问模块
    'allow_module_list' => [
        'api',
        'admin',
        'index',
        'service'
    ],
    // 默认模块名
    'default_module'    => 'index',
    // 禁止访问模块
    'deny_module_list'  => ['common'],
    // 是否支持多模块
    'app_multi_module'  => true,
    // 默认语言
    'default_lang'      => 'zh-cn',
    // URL伪静态后缀
    'url_html_suffix'   => false,
    'session'=>[
        'expire'=>8640000,
    ]

];
