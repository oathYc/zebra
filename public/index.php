<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/*微信打开处理*/
if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {

    if ($_SERVER['SCRIPT_NAME'] == '/index.php'){
        $_SERVER['PATH_INFO'] = '/';
    }
} else {
    $_SERVER['PATH_INFO'] = $_SERVER['REQUEST_URI'];
}
// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
