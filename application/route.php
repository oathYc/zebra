<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

/**
 * V1
 */
////测试
//Route::rule('v1/test','api/Tests/index');
///*用户相关*/
//Route::post('v1/user/add-message','api/User/addMessage');
//
///*token*/
//Route::get('v1/token/token','api/Token/token');
//Route::get('v1/token/refresh','api/Token/refresh');
///*上传图片*/
//Route::post('v1/user/img','api/User/img');
//




return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
