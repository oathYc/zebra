<?php

namespace app\api\validate;

use think\Validate;

/**
 * 生成token参数验证器
 */
class Token extends Validate
{

    protected $rule = [
        'nonce'        => 'require',
        'signKey'        => 'require',
        'timeStamp'    => 'number|require',
        'sign'         => 'require',
        'refreshToken' => 'require'
    ];

    protected $message = [
        'nonce.require'        => '随机数不能为空',
        'signKey.require'        => 'signKey不能为空',
        'timeStamp.number'     => '时间戳格式错误',
        'sign.require'         => '签名不能为空',
        'refreshToken.require' => '刷新token不能为空',
    ];

    protected $scene = [

        'token'   => [
            'nonce',
            'timeStamp',
            'sign',
            'signKey',
        ],
        'refresh' => [
            'nonce',
            'timeStamp',
            'sign',
            'signKey',
            'refreshToken',
        ],
    ];
}