<?php
/**
 * 向客户端发送相应基类
 */

namespace app\api\controller\v1;

use think\Response;
use think\response\Redirect;

trait Send
{

    /**
     * 默认返回资源类型
     * @var string
     */
    protected $restDefaultType = 'json';

    /**
     * 设置响应类型
     * @param null $type
     * @return $this
     */
    public function setType ($type = null)
    {
        $this->type = (string)(!empty($type)) ? $type : $this->restDefaultType;
        return $this;
    }

    /**
     * 返回成功
     */
    public static function successResponse ($data = [], $message = '操作成功！', $code = 200, $header = [])
    {
//        http_response_code($code);    //设置返回头部

        $return['code'] = (int)$code;
        $return['message'] = $message;
        $return['data'] = $data;
        // 返回JSON数据格式到客户端 包含状态信息
        header('Content-Type:application/json; charset=utf-8');

//        // 发送头部信息
//        foreach ($header as $name => $val) {
//            if ( is_null($val) ) {
//                header($name);
//            } else {
//                header($name . ':' . $val);
//            }
//        }
        exit(json_encode($return, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 返回成功
     */
    public static function errorResponse ($message = 'error', $code = -1, $data = [], $header = [])
    {
        //http_response_code($code);    //设置返回头部
        $return['code'] = (int)$code;
        $return['message'] = $message;

        if (!empty($data)){
            $return['data'] = $data;
        }
        // 返回JSON数据格式到客户端 包含状态信息
        header('Content-Type:application/json; charset=utf-8');

//        // 发送头部信息
//        foreach ($header as $name => $val) {
//            if ( is_null($val) ) {
//                header($name);
//            } else {
//                header($name . ':' . $val);
//            }
//        }
        exit(json_encode($return, JSON_UNESCAPED_UNICODE));
    }
}