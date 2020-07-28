<?php

namespace app\api\validate;

use app\api\controller\v1\Send;

/**
 * 公共验证码方法
 * Class Common
 * @package app\api\validate
 */
class ValidataCommon
{
    use Send;
    /**
     * 默认支持验证规则
     * 更多验证规则请使用原生验证器
     * @var array
     */
    public static $dataRule = [
        'require',
        'int',
        'mobile',
        'mailbox',
    ];

    /**
     * 接口参数公共验证方法
     * @param array $rule
     * @param array $data
     */
    static function validateCheck ($rule = [], $data = [])
    {
        if ( is_array($rule) && is_array($data) ) {
            foreach ($rule as $k => $v) {
                if ( !in_array($v, self::$dataRule) ) {
                    self::errorResponse(lang('Current verification rules are not supported'), 100);
                }
                if ( !isset($data[$k]) || empty($data[$k]) ) {
                    self::errorResponse($k . lang('Can not be empty'), 100);
                } else {
                    if ( $v == 'int' ) {
                        if ( !is_numeric($data[$k]) ) {
                            self::errorResponse(lang('Type must be') . $v, 100);
                        }
                    } else if ( $v == 'mobile' ) {
                        if ( !preg_match('/^1[3-9][0-9]\d{8}$/', $data[$k]) ) {
                            self::errorResponse(lang('Malformed phone number'), 100);
                        }
                    } else if ( $v == 'mailbox' ) {
                        if ( !preg_match('/^[a-z0-9]+([._-][a-z0-9]+)*@([0-9a-z]+\.[a-z]{2,14}(\.[a-z]{2})?)$/i', $data[$k]) ) {
                            self::errorResponse(lang('Incorrect mailbox format'), 100);
                        }
                    }
                }
            }
        } else {
            self::errorResponse(lang('Verify data format as an array'));
        }

    }

    /**
     * 陌生刘：💻
     * Notes:检验参数是否存在
     * User: lyc
     * Date: 2019/6/13
     * Time: 16:53
     * @param $data
     * @param $parameter
     */
    public function validateError ($data, $parameter)
    {


    }
}